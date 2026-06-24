<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFulfillment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan customer.
     */
    public function index(): View
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->with([
                'payment',
                'items.umkm',
                'fulfillments.umkm',
            ])
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan customer.
     */
    public function show(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403,
            'Anda tidak memiliki akses ke pesanan ini.'
        );

        $order->load([
            'items.umkm',
            'items.product',
            'payment',
            'fulfillments.umkm',
        ]);

        return view('store.checkout.success', compact('order'));
    }

    /**
     * Customer mengonfirmasi paket dari satu UMKM
     * sudah diterima.
     */
    public function completeFulfillment(
        Order $order,
        OrderFulfillment $fulfillment
    ): RedirectResponse {
        abort_unless(
            $order->user_id === auth()->id(),
            403,
            'Anda tidak memiliki akses ke pesanan ini.'
        );

        abort_unless(
            $fulfillment->order_id === $order->id,
            404,
            'Data pengiriman tidak ditemukan.'
        );

        DB::transaction(function () use (
            $order,
            $fulfillment
        ): void {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            $lockedFulfillment = OrderFulfillment::query()
                ->whereKey($fulfillment->id)
                ->where('order_id', $lockedOrder->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedFulfillment->status !== 'shipped') {
                abort(
                    422,
                    'Pesanan hanya dapat diselesaikan setelah dikirim oleh UMKM.'
                );
            }

            $lockedFulfillment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $this->synchronizeOrderStatus($lockedOrder);
        });

        return redirect()
            ->route('customer.orders.show', $order)
            ->with(
                'success',
                'Terima kasih. Pesanan berhasil dikonfirmasi sudah diterima.'
            );
    }

    /**
     * Menyesuaikan status pesanan utama berdasarkan
     * seluruh fulfillment UMKM.
     */
    private function synchronizeOrderStatus(Order $order): void
    {
        $statuses = OrderFulfillment::query()
            ->where('order_id', $order->id)
            ->pluck('status');

        if ($statuses->isEmpty()) {
            return;
        }

        if (
            $statuses->every(
                fn (string $status): bool => $status === 'completed'
            )
        ) {
            $orderStatus = 'completed';
        } elseif (
            $statuses->every(
                fn (string $status): bool => in_array(
                    $status,
                    [
                        'shipped',
                        'completed',
                    ],
                    true
                )
            )
        ) {
            $orderStatus = 'shipped';
        } elseif (
            $statuses->every(
                fn (string $status): bool => in_array(
                    $status,
                    [
                        'packed',
                        'shipped',
                        'completed',
                    ],
                    true
                )
            )
        ) {
            $orderStatus = 'packed';
        } else {
            $orderStatus = 'processing';
        }

        $order->update([
            'order_status' => $orderStatus,
        ]);
    }
}