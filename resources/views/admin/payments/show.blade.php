
@extends('layouts.dashboard')

@section('title', 'Detail Pembayaran')

@section('content')
    @php
        $order = $payment->order;

        $paymentStatuses = [
            'waiting' => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'paid' => [
                'label' => 'Pembayaran Diterima',
                'class' => 'bg-green-100 text-green-700',
            ],
            'rejected' => [
                'label' => 'Pembayaran Ditolak',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

        $orderStatuses = [
            'pending' => [
                'label' => 'Menunggu Pembayaran',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'processing' => [
                'label' => 'Sedang Diproses',
                'class' => 'bg-blue-100 text-blue-700',
            ],
            'packed' => [
                'label' => 'Sudah Dikemas',
                'class' => 'bg-indigo-100 text-indigo-700',
            ],
            'shipped' => [
                'label' => 'Sedang Dikirim',
                'class' => 'bg-violet-100 text-violet-700',
            ],
            'completed' => [
                'label' => 'Pesanan Selesai',
                'class' => 'bg-green-100 text-green-700',
            ],
            'cancelled' => [
                'label' => 'Pesanan Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

        $paymentStatus = $paymentStatuses[$payment->status]
            ?? [
                'label' => ucfirst($payment->status),
                'class' => 'bg-slate-100 text-slate-700',
            ];

        $orderStatus = $orderStatuses[$order->order_status]
            ?? [
                'label' => ucfirst($order->order_status),
                'class' => 'bg-slate-100 text-slate-700',
            ];

        $groupedItems = $order->items->groupBy('umkm_id');

        $amountMatches = (float) $payment->amount ===
            (float) $order->total_amount;
    @endphp

    {{-- Header --}}
    <div class="mb-8 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                Verifikasi Pembayaran
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Detail Pembayaran
            </h1>

            <p class="mt-2 text-slate-500">
                Periksa informasi transfer dan bukti pembayaran customer.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('admin.payments.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Daftar Pembayaran
            </a>

            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Dashboard
            </a>
        </div>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <p class="font-bold">
                Terdapat kesalahan:
            </p>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan status --}}
    <div class="mb-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Nomor Pesanan
            </p>

            <p class="mt-2 break-all font-mono font-bold text-slate-900">
                {{ $order->order_number }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Status Pembayaran
            </p>

            <span class="mt-3 inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $paymentStatus['class'] }}">
                {{ $paymentStatus['label'] }}
            </span>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Status Pesanan
            </p>

            <span class="mt-3 inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $orderStatus['class'] }}">
                {{ $orderStatus['label'] }}
            </span>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Total Pesanan
            </p>

            <p class="mt-2 text-xl font-bold text-violet-700">
                Rp{{ number_format(
                    (float) $order->total_amount,
                    0,
                    ',',
                    '.'
                ) }}
            </p>
        </div>
    </div>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_420px]">
        {{-- Informasi utama --}}
        <div class="space-y-7">
            {{-- Data transfer --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                            Informasi Transfer
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-slate-900">
                            Data Pembayaran Customer
                        </h2>
                    </div>

                    @if ($amountMatches)
                        <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-bold text-green-700">
                            Nominal Sesuai
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-red-100 px-4 py-2 text-sm font-bold text-red-700">
                            Nominal Berbeda
                        </span>
                    @endif
                </div>

                <dl class="mt-7 grid gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">
                            Nama Pemilik Rekening
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $payment->account_holder_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Tanggal Transfer
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $payment->transfer_date?->format('d M Y') ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Jumlah yang Ditransfer
                        </dt>

                        <dd class="mt-1 text-xl font-bold text-violet-700">
                            Rp{{ number_format(
                                (float) $payment->amount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Total yang Harus Dibayar
                        </dt>

                        <dd class="mt-1 text-xl font-bold text-slate-900">
                            Rp{{ number_format(
                                (float) $order->total_amount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Bukti Dikirim
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Customer
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $payment->user?->name ?? '-' }}
                        </dd>

                        <dd class="mt-1 text-sm text-slate-500">
                            {{ $payment->user?->email ?? '-' }}
                        </dd>
                    </div>
                </dl>

                @unless ($amountMatches)
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5">
                        <p class="font-bold text-red-700">
                            Nominal pembayaran tidak sesuai
                        </p>

                        <p class="mt-2 text-sm leading-6 text-red-600">
                            Periksa kembali bukti transfer sebelum menerima
                            pembayaran ini.
                        </p>
                    </div>
                @endunless
            </div>

            {{-- Informasi penerima --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-bold text-slate-900">
                    Informasi Pengiriman
                </h2>

                <dl class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-500">
                            Nama Penerima
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $order->recipient_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Nomor Telepon
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $order->phone }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm text-slate-500">
                            Alamat
                        </dt>

                        <dd class="mt-1 whitespace-pre-line leading-7 text-slate-900">
                            {{ $order->address }}
                        </dd>
                    </div>

                    @if ($order->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-slate-500">
                                Catatan Pesanan
                            </dt>

                            <dd class="mt-1 whitespace-pre-line leading-7 text-slate-900">
                                {{ $order->notes }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Detail produk --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">
                            Rincian Produk
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ number_format($order->items->sum('quantity')) }}
                            item dari
                            {{ number_format($groupedItems->count()) }}
                            UMKM.
                        </p>
                    </div>

                    <p class="text-sm text-slate-500">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <div class="mt-7 space-y-6">
                    @foreach ($groupedItems as $items)
                        @php
                            $firstItem = $items->first();
                            $umkm = $firstItem?->umkm;

                            $umkmSubtotal = $items->sum(
                                fn ($item) => (float) $item->subtotal
                            );
                        @endphp

                        <div class="overflow-hidden rounded-2xl border border-slate-200">
                            <div class="flex items-center justify-between gap-4 bg-slate-50 px-5 py-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">
                                        UMKM
                                    </p>

                                    <p class="mt-1 font-bold text-slate-900">
                                        {{ $umkm?->business_name ?? 'UMKM Sasirangan' }}
                                    </p>
                                </div>

                                <p class="font-bold text-violet-700">
                                    Rp{{ number_format(
                                        $umkmSubtotal,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach ($items as $item)
                                    <div class="flex flex-col justify-between gap-4 p-5 sm:flex-row">
                                        <div>
                                            <p class="font-bold text-slate-900">
                                                {{ $item->product_name }}
                                            </p>

                                            <p class="mt-1 text-sm text-slate-500">
                                                {{ number_format($item->quantity) }}
                                                ×
                                                Rp{{ number_format(
                                                    (float) $item->price,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </p>
                                        </div>

                                        <p class="font-bold text-slate-900">
                                            Rp{{ number_format(
                                                (float) $item->subtotal,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-7 space-y-3 border-t border-slate-200 pt-6">
                    <div class="flex justify-between gap-4 text-sm">
                        <span class="text-slate-500">
                            Subtotal
                        </span>

                        <span class="font-semibold text-slate-900">
                            Rp{{ number_format(
                                (float) $order->subtotal,
                                0,
                                ',',
                                '.'
                            ) }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4 text-sm">
                        <span class="text-slate-500">
                            Ongkos Kirim
                        </span>

                        <span class="font-semibold text-slate-900">
                            Rp{{ number_format(
                                (float) $order->shipping_cost,
                                0,
                                ',',
                                '.'
                            ) }}
                        </span>
                    </div>

                    <div class="flex items-end justify-between gap-4 border-t border-slate-200 pt-4">
                        <span class="font-bold text-slate-700">
                            Total Pembayaran
                        </span>

                        <span class="text-2xl font-bold text-violet-700">
                            Rp{{ number_format(
                                (float) $order->total_amount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bukti pembayaran dan aksi --}}
        <aside class="space-y-7">
            {{-- Foto bukti --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">
                    Bukti Pembayaran
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Klik gambar untuk melihat bukti pembayaran dalam ukuran
                    penuh.
                </p>

                @if ($payment->payment_proof)
                    <a
                        href="{{ asset('storage/'.$payment->payment_proof) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-6 block overflow-hidden rounded-2xl border border-slate-200 bg-slate-100"
                    >
                        <img
                            src="{{ asset('storage/'.$payment->payment_proof) }}"
                            alt="Bukti pembayaran {{ $order->order_number }}"
                            class="max-h-[620px] w-full object-contain"
                        >
                    </a>

                    <a
                        href="{{ asset('storage/'.$payment->payment_proof) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 flex w-full items-center justify-center rounded-xl border border-violet-200 bg-violet-50 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-100"
                    >
                        Buka Gambar Penuh
                    </a>
                @else
                    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-6 text-center">
                        <p class="font-semibold text-red-700">
                            Bukti pembayaran tidak ditemukan
                        </p>
                    </div>
                @endif
            </div>

            {{-- Aksi verifikasi --}}
            @if ($payment->status === 'waiting')
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900">
                        Verifikasi Pembayaran
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Periksa bukti transfer dan nominal pembayaran sebelum
                        mengambil keputusan.
                    </p>

                    {{-- Terima pembayaran --}}
                    <form
                        method="POST"
                        action="{{ route('admin.payments.approve', $payment) }}"
                        class="mt-6"
                        onsubmit="return confirm('Yakin ingin menerima pembayaran ini? Pesanan akan mulai diproses.')"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-xl bg-green-600 px-5 py-4 font-semibold text-white transition hover:bg-green-700"
                        >
                            Terima Pembayaran
                        </button>
                    </form>

                    <div class="my-6 flex items-center gap-4">
                        <div class="h-px flex-1 bg-slate-200"></div>

                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Atau
                        </span>

                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>

                    {{-- Tolak pembayaran --}}
                    <form
                        method="POST"
                        action="{{ route('admin.payments.reject', $payment) }}"
                        onsubmit="return confirm('Yakin ingin menolak pembayaran ini?')"
                    >
                        @csrf
                        @method('PATCH')

                        <div>
                            <label
                                for="rejection_reason"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Alasan Penolakan
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="rejection_reason"
                                name="rejection_reason"
                                rows="5"
                                maxlength="1000"
                                required
                                placeholder="Contoh: Nominal transfer tidak sesuai atau bukti pembayaran tidak jelas."
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            >{{ old('rejection_reason') }}</textarea>

                            @error('rejection_reason')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="mt-4 flex w-full items-center justify-center rounded-xl bg-red-600 px-5 py-4 font-semibold text-white transition hover:bg-red-700"
                        >
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>
            @else
                {{-- Hasil verifikasi --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900">
                        Hasil Verifikasi
                    </h2>

                    <div class="mt-5 rounded-2xl px-5 py-4 {{ $paymentStatus['class'] }}">
                        <p class="font-bold">
                            {{ $paymentStatus['label'] }}
                        </p>
                    </div>

                    <dl class="mt-6 space-y-5">
                        <div>
                            <dt class="text-sm text-slate-500">
                                Diverifikasi Oleh
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $payment->verifiedBy?->name ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-slate-500">
                                Waktu Verifikasi
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $payment->verified_at?->format('d M Y, H:i') ?? '-' }}
                            </dd>
                        </div>

                        @if ($payment->status === 'rejected')
                            <div>
                                <dt class="text-sm text-slate-500">
                                    Alasan Penolakan
                                </dt>

                                <dd class="mt-2 rounded-xl border border-red-200 bg-red-50 p-4 leading-6 text-red-700">
                                    {{ $payment->rejection_reason
                                        ?: 'Tidak ada alasan penolakan.' }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif
        </aside>
    </div>
@endsection

