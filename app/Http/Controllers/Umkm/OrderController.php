<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFulfillment;
use App\Models\Umkm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan milik UMKM.
     */
    public function index(Request $request): View
    {
        $umkm = $this->getAuthenticatedUmkm();

        $allowedStatuses = [
            'processing',
            'packed',
            'shipped',
            'completed',
            'cancelled',
        ];

        $status = $request->string('status')->toString();

        if (! in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $orders = $this->ordersForUmkm($umkm->id)
            ->with([
                'user',
                'payment',

                'items' => function ($query) use ($umkm) {
                    $query
                        ->where('umkm_id', $umkm->id)
                        ->with('product');
                },

                'fulfillments' => function ($query) use ($umkm) {
                    $query->where('umkm_id', $umkm->id);
                },
            ])
            ->when(
                $status !== '',
                fn (Builder $query) => $query->whereHas(
                    'fulfillments',
                    fn (Builder $fulfillmentQuery) => $fulfillmentQuery
                        ->where('umkm_id', $umkm->id)
                        ->where('status', $status)
                )
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $fulfillmentQuery = OrderFulfillment::query()
            ->where('umkm_id', $umkm->id)
            ->whereHas(
                'order',
                fn (Builder $query) => $query->where(
                    'payment_status',
                    'paid'
                )
            );

        $statistics = [
            'all' => (clone $fulfillmentQuery)->count(),

            'processing' => (clone $fulfillmentQuery)
                ->where('status', 'processing')
                ->count(),

            'packed' => (clone $fulfillmentQuery)
                ->where('status', 'packed')
                ->count(),

            'shipped' => (clone $fulfillmentQuery)
                ->where('status', 'shipped')
                ->count(),

            'completed' => (clone $fulfillmentQuery)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('umkm.orders.index', compact(
            'umkm',
            'orders',
            'statistics',
            'status'
        ));
    }

    /**
     * Menampilkan detail pesanan.
     */
    public function show(Order $order): View
    {
        $umkm = $this->getAuthenticatedUmkm();

        $order = Order::query()
            ->whereKey($order->id)
            ->where('payment_status', 'paid')
            ->whereHas(
                'items',
                fn (Builder $query) => $query->where(
                    'umkm_id',
                    $umkm->id
                )
            )
            ->with([
                'user',
                'payment',

                'items' => function ($query) use ($umkm) {
                    $query
                        ->where('umkm_id', $umkm->id)
                        ->with('product');
                },

                'fulfillments' => function ($query) use ($umkm) {
                    $query->where('umkm_id', $umkm->id);
                },
            ])
            ->firstOrFail();

        $fulfillment = $order->fulfillments->first();

        abort_unless(
            $fulfillment,
            404,
            'Status pemenuhan pesanan tidak ditemukan.'
        );

        $umkmSubtotal = $order->items->sum(
            fn ($item) => (float) $item->subtotal
        );

        $totalItems = $order->items->sum('quantity');

        return view('umkm.orders.show', compact(
            'umkm',
            'order',
            'fulfillment',
            'umkmSubtotal',
            'totalItems'
        ));
    }

    /**
     * Menandai pesanan sudah dikemas.
     */
    public function markAsPacked(Order $order): RedirectResponse
    {
        $umkm = $this->getAuthenticatedUmkm();

        DB::transaction(function () use ($order, $umkm): void {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->where('payment_status', 'paid')
                ->lockForUpdate()
                ->firstOrFail();

            $fulfillment = OrderFulfillment::query()
                ->where('order_id', $lockedOrder->id)
                ->where('umkm_id', $umkm->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($fulfillment->status !== 'processing') {
                abort(
                    422,
                    'Pesanan hanya dapat dikemas ketika statusnya sedang diproses.'
                );
            }

            $fulfillment->update([
                'status' => 'packed',
                'packed_at' => now(),
            ]);

            $this->synchronizeOrderStatus($lockedOrder);
        });

        return redirect()
            ->route('umkm.orders.show', $order)
            ->with(
                'success',
                'Pesanan berhasil ditandai sudah dikemas.'
            );
    }

    /**
     * Menandai pesanan sudah dikirim.
     */
    public function markAsShipped(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'courier' => [
                'required',
                'string',
                'max:100',
            ],

            'tracking_number' => [
                'required',
                'string',
                'max:150',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'courier.required' => 'Nama kurir wajib diisi.',
            'courier.max' => 'Nama kurir maksimal 100 karakter.',

            'tracking_number.required' => 'Nomor resi wajib diisi.',
            'tracking_number.max' => 'Nomor resi maksimal 150 karakter.',

            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        $umkm = $this->getAuthenticatedUmkm();

        DB::transaction(function () use (
            $order,
            $umkm,
            $validated
        ): void {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->where('payment_status', 'paid')
                ->lockForUpdate()
                ->firstOrFail();

            $fulfillment = OrderFulfillment::query()
                ->where('order_id', $lockedOrder->id)
                ->where('umkm_id', $umkm->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($fulfillment->status !== 'packed') {
                abort(
                    422,
                    'Pesanan hanya dapat dikirim setelah ditandai sudah dikemas.'
                );
            }

            $fulfillment->update([
                'status' => 'shipped',
                'courier' => $validated['courier'],
                'tracking_number' => $validated['tracking_number'],
                'notes' => $validated['notes'] ?? null,
                'shipped_at' => now(),
            ]);

            $this->synchronizeOrderStatus($lockedOrder);
        });

        return redirect()
            ->route('umkm.orders.show', $order)
            ->with(
                'success',
                'Pesanan berhasil ditandai sudah dikirim.'
            );
    }

    /**
     * Mengambil profil UMKM pengguna yang login.
     */
    private function getAuthenticatedUmkm(): Umkm
    {
        $umkm = Umkm::query()
            ->where('user_id', auth()->id())
            ->first();

        abort_unless(
            $umkm,
            403,
            'Profil UMKM tidak ditemukan.'
        );

        abort_unless(
            $umkm->verification_status === 'active',
            403,
            'UMKM belum aktif atau belum disetujui Admin.'
        );

        return $umkm;
    }

    /**
     * Query dasar pesanan berdasarkan UMKM.
     */
    private function ordersForUmkm(int $umkmId): Builder
    {
        return Order::query()
            ->where('payment_status', 'paid')
            ->whereHas(
                'items',
                fn (Builder $query) => $query->where(
                    'umkm_id',
                    $umkmId
                )
            )
            ->whereHas(
                'fulfillments',
                fn (Builder $query) => $query->where(
                    'umkm_id',
                    $umkmId
                )
            );
    }

    /**
     * Menyesuaikan status pesanan utama berdasarkan
     * seluruh status fulfillment UMKM.
     */
    private function synchronizeOrderStatus(Order $order): void
    {
        $statuses = OrderFulfillment::query()
            ->where('order_id', $order->id)
            ->pluck('status');

        if ($statuses->isEmpty()) {
            return;
        }

        if ($statuses->every(
            fn (string $status) => $status === 'completed'
        )) {
            $orderStatus = 'completed';
        } elseif ($statuses->every(
            fn (string $status) => in_array(
                $status,
                ['shipped', 'completed'],
                true
            )
        )) {
            $orderStatus = 'shipped';
        } elseif ($statuses->every(
            fn (string $status) => in_array(
                $status,
                ['packed', 'shipped', 'completed'],
                true
            )
        )) {
            $orderStatus = 'packed';
        } else {
            $orderStatus = 'processing';
        }

        $order->update([
            'order_status' => $orderStatus,
        ]);
    }
}
