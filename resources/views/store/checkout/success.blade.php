@extends('layouts.store')

@section('title', 'Detail Pesanan')

@section('content')
    @php
        $paymentStatuses = [
            'unpaid' => [
                'label' => 'Belum Dibayar',
                'class' => 'bg-slate-100 text-slate-700',
            ],
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

        $fulfillmentStatuses = [
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
                'label' => 'Sudah Diterima',
                'class' => 'bg-green-100 text-green-700',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

        $paymentStatus = $paymentStatuses[$order->payment_status]
            ?? [
                'label' => ucfirst($order->payment_status),
                'class' => 'bg-slate-100 text-slate-700',
            ];

        $orderStatus = $orderStatuses[$order->order_status]
            ?? [
                'label' => ucfirst($order->order_status),
                'class' => 'bg-slate-100 text-slate-700',
            ];

        $groupedItems = $order->items->groupBy('umkm_id');

        $fulfillments = $order->relationLoaded('fulfillments')
            ? $order->fulfillments
            : collect();

        $isCheckoutSuccess = request()->routeIs(
            'customer.checkout.success'
        );
    @endphp

    <section class="bg-slate-50 py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                        Pesanan Customer
                    </p>

                    <h1 class="mt-2 text-3xl font-bold text-slate-900">
                        Detail Pesanan
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Nomor pesanan
                        <span class="font-mono font-bold text-slate-800">
                            {{ $order->order_number }}
                        </span>
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if (Route::has('customer.orders.index'))
                        <a
                            href="{{ route('customer.orders.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Pesanan Saya
                        </a>
                    @endif

                    <a
                        href="{{ route('store.catalog') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                    >
                        Belanja Lagi
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

            {{-- Pesanan baru berhasil --}}
            @if ($isCheckoutSuccess)
                <div class="mb-8 overflow-hidden rounded-3xl border border-green-200 bg-green-50 p-6 shadow-sm sm:p-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="h-8 w-8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m4.5 12.75 6 6 9-13.5"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-green-900">
                                Pesanan berhasil dibuat
                            </h2>

                            <p class="mt-2 text-sm leading-7 text-green-700">
                                Pesanan sudah tersimpan. Silakan lakukan
                                pembayaran dan unggah bukti transfer agar
                                pesanan dapat diproses.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Ringkasan status --}}
            <div class="mb-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">
                        Nomor Pesanan
                    </p>

                    <p class="mt-2 break-all font-mono font-bold text-slate-900">
                        {{ $order->order_number }}
                    </p>

                    <p class="mt-2 text-xs text-slate-400">
                        {{ $order->created_at->format('d M Y, H:i') }}
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

                <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5 shadow-sm">
                    <p class="text-sm text-violet-700">
                        Total Pembayaran
                    </p>

                    <p class="mt-2 text-2xl font-bold text-violet-800">
                        Rp{{ number_format(
                            (float) $order->total_amount,
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>
                </div>
            </div>

            <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">
                {{-- Kolom utama --}}
                <div class="space-y-7">
                    {{-- Produk yang dibeli --}}
                    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                            <h2 class="text-xl font-bold text-slate-900">
                                Produk yang Dibeli
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ number_format(
                                    $order->items->sum('quantity')
                                ) }}
                                item dari
                                {{ number_format($groupedItems->count()) }}
                                UMKM.
                            </p>
                        </div>

                        <div class="space-y-6 p-6 sm:p-8">
                            @forelse ($groupedItems as $items)
                                @php
                                    $firstItem = $items->first();

                                    $umkm = $firstItem?->umkm;

                                    $umkmSubtotal = $items->sum(
                                        fn ($item) => (float) $item->subtotal
                                    );
                                @endphp

                                <div class="overflow-hidden rounded-2xl border border-slate-200">
                                    <div class="flex flex-col justify-between gap-4 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center">
                                        <div class="flex items-center gap-3">
                                            @if ($umkm?->logo)
                                                <img
                                                    src="{{ asset('storage/'.$umkm->logo) }}"
                                                    alt="{{ $umkm->business_name }}"
                                                    class="h-11 w-11 rounded-xl border border-slate-200 object-cover"
                                                >
                                            @else
                                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 font-bold text-violet-700">
                                                    {{ strtoupper(
                                                        substr(
                                                            $umkm?->business_name
                                                                ?? 'U',
                                                            0,
                                                            1
                                                        )
                                                    ) }}
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">
                                                    UMKM
                                                </p>

                                                <p class="mt-1 font-bold text-slate-900">
                                                    {{ $umkm?->business_name
                                                        ?? 'UMKM Sasirangan' }}
                                                </p>
                                            </div>
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
                                            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center">
                                                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                    @if ($item->product?->main_image)
                                                        <img
                                                            src="{{ asset(
                                                                'storage/'
                                                                .$item->product->main_image
                                                            ) }}"
                                                            alt="{{ $item->product_name }}"
                                                            class="h-full w-full object-cover"
                                                        >
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-xs font-semibold text-slate-400">
                                                            Tidak ada foto
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <p class="font-bold text-slate-900">
                                                        {{ $item->product_name }}
                                                    </p>

                                                    <p class="mt-1 text-sm text-slate-500">
                                                        {{ number_format(
                                                            $item->quantity
                                                        ) }}
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
                            @empty
                                <div class="rounded-2xl bg-slate-50 p-8 text-center text-sm text-slate-500">
                                    Produk pesanan tidak ditemukan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Informasi pengiriman --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                            Tujuan Pengiriman
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-slate-900">
                            Informasi Penerima
                        </h2>

                        <dl class="mt-7 grid gap-6 sm:grid-cols-2">
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
                                    Alamat Lengkap
                                </dt>

                                <dd class="mt-2 whitespace-pre-line rounded-2xl bg-slate-50 p-5 leading-7 text-slate-900">
                                    {{ $order->address }}
                                </dd>
                            </div>

                            @if ($order->notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm text-slate-500">
                                        Catatan Pesanan
                                    </dt>

                                    <dd class="mt-2 whitespace-pre-line rounded-2xl border border-amber-200 bg-amber-50 p-5 leading-7 text-amber-800">
                                        {{ $order->notes }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="space-y-7">
                    {{-- Ringkasan pembayaran --}}
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Ringkasan Pembayaran
                        </h2>

                        <div class="mt-6 space-y-4">
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
                                    Total
                                </span>

                                <span class="text-xl font-bold text-violet-700">
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

                    {{-- Aksi pembayaran --}}
                    @if (
                        in_array(
                            $order->payment_status,
                            ['unpaid', 'rejected'],
                            true
                        )
                    )
                        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-amber-900">
                                Pembayaran Belum Selesai
                            </h2>

                            @if ($order->payment_status === 'rejected')
                                <p class="mt-2 text-sm leading-6 text-amber-800">
                                    Bukti pembayaran sebelumnya ditolak.
                                    Silakan periksa alasannya lalu unggah bukti
                                    pembayaran yang baru.
                                </p>

                                @if ($order->payment?->rejection_reason)
                                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-700">
                                        <strong>Alasan penolakan:</strong>

                                        {{ $order->payment->rejection_reason }}
                                    </div>
                                @endif
                            @else
                                <p class="mt-2 text-sm leading-6 text-amber-800">
                                    Silakan transfer sesuai total pesanan,
                                    kemudian unggah bukti pembayaran.
                                </p>
                            @endif

                            @if (Route::has('customer.payment.create'))
                                <a
                                    href="{{ route(
                                        'customer.payment.create',
                                        $order
                                    ) }}"
                                    class="mt-5 flex w-full items-center justify-center rounded-xl bg-violet-700 px-5 py-4 font-semibold text-white transition hover:bg-violet-800"
                                >
                                    {{ $order->payment_status === 'rejected'
                                        ? 'Upload Ulang Bukti'
                                        : 'Bayar Sekarang' }}
                                </a>
                            @endif
                        </div>
                    @elseif ($order->payment_status === 'waiting')
                        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-6 w-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 6v6l4 2"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                    ></circle>
                                </svg>
                            </div>

                            <h2 class="mt-5 text-lg font-bold text-amber-900">
                                Menunggu Verifikasi Admin
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-amber-800">
                                Bukti pembayaran sudah dikirim dan sedang
                                diperiksa oleh Admin SasiVerse.
                            </p>
                        </div>
                    @elseif ($order->payment_status === 'paid')
                        <div class="rounded-3xl border border-green-200 bg-green-50 p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                    class="h-6 w-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m4.5 12.75 6 6 9-13.5"
                                    />
                                </svg>
                            </div>

                            <h2 class="mt-5 text-lg font-bold text-green-900">
                                Pembayaran Diterima
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-green-700">
                                Admin sudah menerima pembayaran. Pesanan telah
                                diteruskan kepada UMKM untuk diproses.
                            </p>

                            @if ($order->payment?->verified_at)
                                <p class="mt-4 text-xs font-semibold text-green-700">
                                    Diverifikasi pada
                                    {{ $order->payment->verified_at->format(
                                        'd M Y, H:i'
                                    ) }}
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Detail bukti pembayaran --}}
                    @if ($order->payment)
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900">
                                Data Pembayaran
                            </h2>

                            <dl class="mt-6 space-y-5">
                                <div>
                                    <dt class="text-sm text-slate-500">
                                        Nama Pemilik Rekening
                                    </dt>

                                    <dd class="mt-1 font-semibold text-slate-900">
                                        {{ $order->payment->account_holder_name }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm text-slate-500">
                                        Tanggal Transfer
                                    </dt>

                                    <dd class="mt-1 font-semibold text-slate-900">
                                        {{ $order->payment->transfer_date?->format(
                                            'd M Y'
                                        ) ?? '-' }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm text-slate-500">
                                        Jumlah Transfer
                                    </dt>

                                    <dd class="mt-1 font-bold text-violet-700">
                                        Rp{{ number_format(
                                            (float) $order->payment->amount,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </dd>
                                </div>

                                @if ($order->payment->payment_proof)
                                    <div>
                                        <dt class="text-sm text-slate-500">
                                            Bukti Pembayaran
                                        </dt>

                                        <dd class="mt-2">
                                            <a
                                                href="{{ asset(
                                                    'storage/'
                                                    .$order->payment->payment_proof
                                                ) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="font-semibold text-violet-700 hover:underline"
                                            >
                                                Lihat bukti pembayaran
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif
                </aside>
            </div>

            {{-- Status pengiriman per UMKM --}}
            @if ($order->payment_status === 'paid')
                <section class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                            Pengiriman Pesanan
                        </p>

                        <h2 class="mt-2 text-xl font-bold text-slate-900">
                            Status Pengiriman per UMKM
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Setiap UMKM dapat mengirim produknya secara
                            terpisah. Konfirmasi penerimaan setelah paket
                            benar-benar sampai.
                        </p>
                    </div>

                    @if ($fulfillments->isEmpty())
                        <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
                            <p class="font-semibold text-slate-700">
                                Informasi pengiriman belum tersedia
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                UMKM sedang mempersiapkan pesanan Anda.
                            </p>
                        </div>
                    @else
                        <div class="mt-7 space-y-5">
                            @foreach ($fulfillments as $fulfillment)
                                @php
                                    $fulfillmentStatus =
                                        $fulfillmentStatuses[
                                            $fulfillment->status
                                        ]
                                        ?? [
                                            'label' => ucfirst(
                                                $fulfillment->status
                                            ),
                                            'class' => 'bg-slate-100 text-slate-700',
                                        ];

                                    $fulfillmentItems = $order->items
                                        ->where(
                                            'umkm_id',
                                            $fulfillment->umkm_id
                                        );
                                @endphp

                                <div class="rounded-2xl border border-slate-200 p-5 sm:p-6">
                                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">
                                                UMKM
                                            </p>

                                            <h3 class="mt-1 text-lg font-bold text-slate-900">
                                                {{ $fulfillment->umkm?->business_name
                                                    ?? 'UMKM Sasirangan' }}
                                            </h3>

                                            <p class="mt-2 text-sm text-slate-500">
                                                {{ number_format(
                                                    $fulfillmentItems->sum(
                                                        'quantity'
                                                    )
                                                ) }}
                                                item
                                            </p>
                                        </div>

                                        <span class="inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $fulfillmentStatus['class'] }}">
                                            {{ $fulfillmentStatus['label'] }}
                                        </span>
                                    </div>

                                    @if ($fulfillmentItems->isNotEmpty())
                                        <div class="mt-5 rounded-xl bg-slate-50 p-4">
                                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                Produk
                                            </p>

                                            <div class="mt-3 space-y-2">
                                                @foreach ($fulfillmentItems as $item)
                                                    <div class="flex justify-between gap-4 text-sm">
                                                        <span class="text-slate-700">
                                                            {{ $item->product_name }}
                                                            × {{ $item->quantity }}
                                                        </span>

                                                        <span class="font-semibold text-slate-900">
                                                            Rp{{ number_format(
                                                                (float) $item->subtotal,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if ($fulfillment->status === 'processing')
                                        <div class="mt-5 rounded-xl bg-blue-50 p-4 text-sm leading-6 text-blue-700">
                                            UMKM sedang menyiapkan produk
                                            pesanan Anda.
                                        </div>
                                    @elseif ($fulfillment->status === 'packed')
                                        <div class="mt-5 rounded-xl bg-indigo-50 p-4 text-sm leading-6 text-indigo-700">
                                            Produk sudah dikemas dan menunggu
                                            diserahkan kepada kurir.

                                            @if ($fulfillment->packed_at)
                                                <p class="mt-2 text-xs font-semibold">
                                                    Dikemas pada
                                                    {{ $fulfillment->packed_at->format(
                                                        'd M Y, H:i'
                                                    ) }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif ($fulfillment->status === 'shipped')
                                        <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                            <div>
                                                <p class="text-sm text-slate-500">
                                                    Kurir
                                                </p>

                                                <p class="mt-1 font-bold text-slate-900">
                                                    {{ $fulfillment->courier
                                                        ?? '-' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-sm text-slate-500">
                                                    Nomor Resi
                                                </p>

                                                <p class="mt-1 break-all font-mono font-bold text-violet-700">
                                                    {{ $fulfillment->tracking_number
                                                        ?? '-' }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-sm text-slate-500">
                                                    Dikirim Pada
                                                </p>

                                                <p class="mt-1 font-semibold text-slate-900">
                                                    {{ $fulfillment->shipped_at?->format(
                                                        'd M Y, H:i'
                                                    ) ?? '-' }}
                                                </p>
                                            </div>

                                            @if ($fulfillment->notes)
                                                <div class="sm:col-span-2">
                                                    <p class="text-sm text-slate-500">
                                                        Catatan Pengiriman
                                                    </p>

                                                    <div class="mt-2 whitespace-pre-line rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                                                        {{ $fulfillment->notes }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if (
                                            Route::has(
                                                'customer.orders.fulfillments.complete'
                                            )
                                        )
                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'customer.orders.fulfillments.complete',
                                                    [
                                                        'order' => $order,
                                                        'fulfillment' => $fulfillment,
                                                    ]
                                                ) }}"
                                                class="mt-6"
                                                onsubmit="return confirm('Pastikan paket sudah benar-benar diterima. Lanjutkan konfirmasi?')"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-4 font-semibold text-white transition hover:bg-green-700"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.8"
                                                        stroke="currentColor"
                                                        class="h-5 w-5"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="m4.5 12.75 6 6 9-13.5"
                                                        />
                                                    </svg>

                                                    Pesanan Sudah Diterima
                                                </button>
                                            </form>
                                        @endif
                                    @elseif ($fulfillment->status === 'completed')
                                        <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-5">
                                            <p class="font-bold text-green-800">
                                                Pesanan sudah diterima
                                            </p>

                                            <p class="mt-2 text-sm text-green-700">
                                                Dikonfirmasi pada
                                                {{ $fulfillment->completed_at?->format(
                                                    'd M Y, H:i'
                                                ) ?? '-' }}
                                            </p>
                                        </div>
                                    @elseif ($fulfillment->status === 'cancelled')
                                        <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                                            Pengiriman dari UMKM ini telah
                                            dibatalkan.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
        </div>
    </section>
@endsection