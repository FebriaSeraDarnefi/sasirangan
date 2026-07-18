<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Menampilkan formulir upload bukti pembayaran.
     */
    public function create(
        Order $order
    ): View|RedirectResponse {
        $this->ensureOrderOwnedByCustomer($order);

        $order->load([
            'items.umkm',
            'items.product',
            'payment',
        ]);

        if ($order->payment_status === 'waiting') {
            return redirect()
                ->route(
                    'customer.checkout.success',
                    $order
                )
                ->with(
                    'error',
                    'Bukti pembayaran sedang menunggu verifikasi Admin.'
                );
        }

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route(
                    'customer.checkout.success',
                    $order
                )
                ->with(
                    'error',
                    'Pesanan ini sudah dibayar.'
                );
        }

        if ($order->order_status === 'cancelled') {
            return redirect()
                ->route('customer.dashboard')
                ->with(
                    'error',
                    'Pesanan yang sudah dibatalkan tidak dapat dibayar.'
                );
        }

        $paymentGroups = $this->buildPaymentGroups(
            $order
        );

        $hasIncompleteBankDetails =
            $this->hasIncompleteBankDetails(
                $paymentGroups
            );

        return view(
            'store.payment.create',
            compact(
                'order',
                'paymentGroups',
                'hasIncompleteBankDetails'
            )
        );
    }

    /**
     * Menyimpan atau memperbarui bukti pembayaran.
     */
    public function store(
        Request $request,
        Order $order
    ): RedirectResponse {
        $this->ensureOrderOwnedByCustomer($order);

        $order->loadMissing([
            'items.umkm',
            'payment',
        ]);

        if (! $order->canUploadPayment()) {
            return redirect()
                ->route(
                    'customer.checkout.success',
                    $order
                )
                ->with(
                    'error',
                    'Bukti pembayaran tidak dapat diunggah untuk pesanan ini.'
                );
        }

        if ($order->order_status === 'cancelled') {
            return redirect()
                ->route('customer.dashboard')
                ->with(
                    'error',
                    'Pesanan yang sudah dibatalkan tidak dapat dibayar.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Periksa Rekening Setiap UMKM
        |--------------------------------------------------------------------------
        |
        | Bukti pembayaran tidak boleh dikirim apabila salah satu UMKM belum
        | melengkapi nama bank, nomor rekening, atau nama pemilik rekening.
        |
        */

        $paymentGroups = $this->buildPaymentGroups(
            $order
        );

        if (
            $this->hasIncompleteBankDetails(
                $paymentGroups
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment' =>
                        'Pembayaran belum dapat dilakukan karena terdapat UMKM yang belum melengkapi informasi rekening.',
                ]);
        }

        $validated = $request->validate([
            'account_holder_name' => [
                'required',
                'string',
                'max:255',
            ],

            'transfer_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'payment_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ], [
            'account_holder_name.required' =>
                'Nama pemilik rekening pengirim wajib diisi.',

            'transfer_date.required' =>
                'Tanggal transfer wajib diisi.',

            'transfer_date.before_or_equal' =>
                'Tanggal transfer tidak boleh melebihi hari ini.',

            'payment_proof.required' =>
                'Bukti pembayaran wajib diunggah.',

            'payment_proof.image' =>
                'Bukti pembayaran harus berupa gambar.',

            'payment_proof.mimes' =>
                'Format bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',

            'payment_proof.max' =>
                'Ukuran bukti pembayaran maksimal 4 MB.',
        ]);

        $newPaymentProof = $request
            ->file('payment_proof')
            ->store(
                'payments',
                'public'
            );

        $oldPaymentProof =
            $order->payment?->payment_proof;

        try {
            DB::transaction(function () use (
                $validated,
                $order,
                $newPaymentProof
            ): void {
                $payment = Payment::query()
                    ->where(
                        'order_id',
                        $order->id
                    )
                    ->lockForUpdate()
                    ->first();

                $paymentData = [
                    'user_id' =>
                        auth()->id(),

                    'account_holder_name' =>
                        $validated[
                            'account_holder_name'
                        ],

                    'transfer_date' =>
                        $validated[
                            'transfer_date'
                        ],

                    'amount' =>
                        $order->total_amount,

                    'payment_proof' =>
                        $newPaymentProof,

                    'status' =>
                        'waiting',

                    'rejection_reason' =>
                        null,

                    'verified_by' =>
                        null,

                    'verified_at' =>
                        null,
                ];

                if ($payment) {
                    $payment->update(
                        $paymentData
                    );
                } else {
                    Payment::create([
                        'order_id' =>
                            $order->id,

                        ...$paymentData,
                    ]);
                }

                $order->update([
                    'payment_status' =>
                        'waiting',
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')
                ->delete(
                    $newPaymentProof
                );

            throw $exception;
        }

        if (
            $oldPaymentProof
            && $oldPaymentProof
                !== $newPaymentProof
        ) {
            Storage::disk('public')
                ->delete(
                    $oldPaymentProof
                );
        }

        return redirect()
            ->route(
                'customer.checkout.success',
                $order
            )
            ->with(
                'success',
                'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi Admin.'
            );
    }

    /**
     * Mengelompokkan item pesanan berdasarkan UMKM.
     */
    private function buildPaymentGroups(
        Order $order
    ): Collection {
        return $order->items
            ->groupBy('umkm_id')
            ->map(function (
                Collection $items
            ): array {
                $umkm = $items
                    ->first()
                    ?->umkm;

                return [
                    'umkm' => $umkm,

                    'items' => $items,

                    'subtotal' => (float) $items
                        ->sum('subtotal'),
                ];
            })
            ->values();
    }

    /**
     * Memeriksa kelengkapan rekening UMKM.
     */
    private function hasIncompleteBankDetails(
        Collection $paymentGroups
    ): bool {
        return $paymentGroups->contains(
            function (array $group): bool {
                $umkm = $group['umkm'];

                return ! $umkm
                    || blank($umkm->bank_name)
                    || blank(
                        $umkm->bank_account_number
                    )
                    || blank(
                        $umkm->bank_account_name
                    );
            }
        );
    }

    /**
     * Memastikan pesanan dimiliki customer yang sedang login.
     */
    private function ensureOrderOwnedByCustomer(
        Order $order
    ): void {
        abort_unless(
            $order->user_id
                === auth()->id(),
            403
        );
    }
}