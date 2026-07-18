@extends('layouts.store')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    @php
        $existingPayment = $order->payment;
    @endphp

    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <div class="mb-7 flex flex-wrap items-center gap-2 text-sm text-slate-500">
            <a
                href="{{ route('store.home') }}"
                class="transition hover:text-violet-700"
            >
                Beranda
            </a>

            <span>/</span>

            <a
                href="{{ route('customer.checkout.success', $order) }}"
                class="transition hover:text-violet-700"
            >
                Detail Pesanan
            </a>

            <span>/</span>

            <span class="font-medium text-slate-800">
                Pembayaran
            </span>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_340px]">
            {{-- Form upload --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                    Pembayaran Manual
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    Upload Bukti Pembayaran
                </h1>

                <p class="mt-3 leading-7 text-slate-500">
                    Pastikan pembayaran sudah ditransfer ke rekening SasiVerse
                    sebelum mengunggah bukti pembayaran.
                </p>

                @if ($order->payment_status === 'rejected')
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5">
                        <p class="font-bold text-red-700">
                            Pembayaran sebelumnya ditolak
                        </p>

                        <p class="mt-2 text-sm leading-6 text-red-600">
                            {{ $existingPayment?->rejection_reason
                                ?: 'Silakan unggah kembali bukti pembayaran yang benar.' }}
                        </p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                        <p class="font-bold">
                            Data pembayaran belum benar:
                        </p>

                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('customer.payment.store', $order) }}"
                    enctype="multipart/form-data"
                    class="mt-8 space-y-6"
                >
                    @csrf

                    <div>
                        <label
                            for="account_holder_name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nama Pemilik Rekening
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="account_holder_name"
                            type="text"
                            name="account_holder_name"
                            value="{{ old(
                                'account_holder_name',
                                $existingPayment?->account_holder_name
                            ) }}"
                            required
                            maxlength="255"
                            placeholder="Nama yang digunakan saat transfer"
                            class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                        >

                        @error('account_holder_name')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="transfer_date"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Tanggal Transfer
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="transfer_date"
                            type="date"
                            name="transfer_date"
                            value="{{ old(
                                'transfer_date',
                                $existingPayment?->transfer_date?->format('Y-m-d')
                                    ?? now()->format('Y-m-d')
                            ) }}"
                            max="{{ now()->format('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                        >

                        @error('transfer_date')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Jumlah Pembayaran
                        </label>

                        <div class="rounded-xl border border-violet-200 bg-violet-50 px-5 py-4">
                            <p class="text-2xl font-bold text-violet-700">
                                Rp{{ number_format(
                                    (float) $order->total_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </p>

                            <p class="mt-1 text-xs text-violet-700/70">
                                Jumlah pembayaran diambil otomatis dari total pesanan.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label
                            for="payment_proof"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Foto Bukti Pembayaran
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="payment_proof"
                            type="file"
                            name="payment_proof"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            required
                            class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-violet-100 file:px-5 file:py-3 file:font-semibold file:text-violet-700 hover:file:bg-violet-200"
                        >

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal
                            4 MB.
                        </p>

                        @error('payment_proof')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row">
                        <button
                            type="submit"
                            onclick="return confirm('Kirim bukti pembayaran untuk diverifikasi Admin?')"
                            class="flex flex-1 items-center justify-center rounded-xl bg-violet-700 px-6 py-4 font-semibold text-white transition hover:bg-violet-800"
                        >
                            Kirim Bukti Pembayaran
                        </button>

                        <a
                            href="{{ route('customer.checkout.success', $order) }}"
                            class="flex flex-1 items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-4 font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

            {{-- Detail pembayaran --}}
            <aside class="space-y-6">
                <div class="rounded-3xl border border-violet-200 bg-violet-50 p-6">
                    <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                        Rekening Tujuan
                    </p>

                    <div class="mt-5 space-y-5 rounded-2xl bg-white p-5 shadow-sm">
                        <div>
                            <p class="text-sm text-slate-500">
                                Bank
                            </p>

                            <p class="mt-1 text-lg font-bold text-slate-900">
{{ $umkm?->bank_name ?: config('payment.bank_name') }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-5">
                            <p class="text-sm text-slate-500">
                                Nomor Rekening
                            </p>

                            <p class="mt-1 break-all font-mono text-xl font-bold text-violet-700">
{{ $umkm?->bank_account_number ?: config('payment.account_number') }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-5">
                            <p class="text-sm text-slate-500">
                                Atas Nama
                            </p>

                            <p class="mt-1 font-bold text-slate-900">
{{ $umkm?->bank_account_name ?: config('payment.account_holder') }}                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm text-slate-500">
                        Nomor Pesanan
                    </p>

                    <p class="mt-1 font-mono font-bold text-slate-900">
                        {{ $order->order_number }}
                    </p>

                    <div class="mt-5 border-t border-slate-200 pt-5">
                        <p class="text-sm text-slate-500">
                            Total Pembayaran
                        </p>

                        <p class="mt-1 text-2xl font-bold text-violet-700">
                            Rp{{ number_format(
                                (float) $order->total_amount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <p class="font-semibold text-amber-900">
                        Periksa sebelum mengirim
                    </p>

                    <p class="mt-2 text-sm leading-6 text-amber-700">
                        Pastikan nomor rekening, nominal transfer, dan foto
                        bukti pembayaran terlihat jelas.
                    </p>
                </div>
            </aside>
        </div>
    </section>
@endsection
