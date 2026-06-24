<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFulfillment;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentVerificationController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index(Request $request): View
    {
        $allowedStatuses = [
            'waiting',
            'paid',
            'rejected',
        ];

        $status = $request->string('status')->toString();

        if (! in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $payments = Payment::query()
            ->with([
                'user',
                'order.items',
            ])
            ->when(
                $status !== '',
                fn ($query) => $query->where('status', $status)
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $statistics = [
            'all' => Payment::query()->count(),

            'waiting' => Payment::query()
                ->where('status', 'waiting')
                ->count(),

            'paid' => Payment::query()
                ->where('status', 'paid')
                ->count(),

            'rejected' => Payment::query()
                ->where('status', 'rejected')
                ->count(),

            'paid_amount' => Payment::query()
                ->where('status', 'paid')
                ->sum('amount'),
        ];

        return view('admin.payments.index', compact(
            'payments',
            'statistics',
            'status'
        ));
    }

    /**
     * Menampilkan detail pembayaran.
     */
    public function show(Payment $payment): View
    {
        $payment->load([
            'user',
            'verifiedBy',
            'order.user',
            'order.items.umkm',
            'order.items.product',
            'order.fulfillments.umkm',
        ]);

        return view(
            'admin.payments.show',
            compact('payment')
        );
    }

    /**
     * Menerima pembayaran customer.
     */
    public function approve(Payment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment): void {
            $lockedPayment = Payment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $order = Order::query()
                ->whereKey($lockedPayment->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPayment->status !== 'waiting') {
                abort(
                    422,
                    'Pembayaran ini sudah diverifikasi sebelumnya.'
                );
            }

            if ($order->order_status === 'cancelled') {
                abort(
                    422,
                    'Pembayaran pesanan yang dibatalkan tidak dapat diterima.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Perbarui pembayaran
            |--------------------------------------------------------------------------
            */

            $lockedPayment->update([
                'status' => 'paid',
                'rejection_reason' => null,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Perbarui status pesanan utama
            |--------------------------------------------------------------------------
            */

            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Buat pemenuhan pesanan untuk setiap UMKM
            |--------------------------------------------------------------------------
            */

            $umkmIds = $order->items()
                ->whereNotNull('umkm_id')
                ->select('umkm_id')
                ->distinct()
                ->pluck('umkm_id');

            foreach ($umkmIds as $umkmId) {
                OrderFulfillment::query()->firstOrCreate(
                    [
                        'order_id' => $order->id,
                        'umkm_id' => $umkmId,
                    ],
                    [
                        'status' => 'processing',
                        'courier' => null,
                        'tracking_number' => null,
                        'notes' => null,
                        'packed_at' => null,
                        'shipped_at' => null,
                        'completed_at' => null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Pembayaran berhasil diterima dan pesanan telah diteruskan kepada UMKM.'
            );
    }

    /**
     * Menolak pembayaran customer.
     */
    public function reject(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use (
            $payment,
            $validated
        ): void {
            $lockedPayment = Payment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            $order = Order::query()
                ->whereKey($lockedPayment->order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPayment->status !== 'waiting') {
                abort(
                    422,
                    'Pembayaran ini sudah diverifikasi sebelumnya.'
                );
            }

            if ($order->order_status === 'cancelled') {
                abort(
                    422,
                    'Pembayaran pesanan yang dibatalkan tidak dapat diverifikasi.'
                );
            }

            $lockedPayment->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $order->update([
                'payment_status' => 'rejected',
                'order_status' => 'pending',
            ]);
        });

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Pembayaran ditolak. Customer dapat mengunggah ulang bukti pembayaran.'
            );
    }
}
