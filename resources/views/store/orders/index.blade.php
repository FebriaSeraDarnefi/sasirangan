@extends('layouts.store')

@section('title', 'Pesanan Saya')

@section('content')
    @php
        $paymentStatuses = [
            'unpaid' => [
                'label' => 'Belum Dibayar',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'waiting' => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'bg-blue-100 text-blue-700',
            ],
            'paid' => [
                'label' => 'Sudah Dibayar',
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
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];
    @endphp

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
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
                href="{{ route('customer.dashboard') }}"
                class="transition hover:text-violet-700"
            >
                Dashboard
            </a>

            <span>/</span>

            <span class="font-medium text-slate-800">
                Pesanan Saya
            </span>
        </div>

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                    Aktivitas Belanja
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">
                    Pesanan Saya
                </h1>

                <p class="mt-3 max-w-2xl leading-7 text-slate-500">
                    Lihat status pembayaran, proses pesanan, dan detail
                    pembelian Sasirangan kamu.
                </p>
            </div>

            <a
                href="{{ route('store.catalog') }}"
                class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
            >
                Belanja Lagi
            </a>
        </div>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="mt-7 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if (session('error'))
            <div class="mt-7 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            {{-- Belum ada pesanan --}}
            <div class="mt-10 rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-violet-100 text-violet-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-10 w-10"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 7.5h12l1 13H5l1-13Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 9V6a3 3 0 0 1 6 0v3"
                        />
                    </svg>
                </div>

                <h2 class="mt-6 text-2xl font-bold text-slate-900">
                    Belum ada pesanan
                </h2>

                <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-500">
                    Pesanan yang sudah dibuat akan muncul di halaman ini.
                    Silakan jelajahi katalog Sasirangan terlebih dahulu.
                </p>

                <a
                    href="{{ route('store.catalog') }}"
                    class="mt-7 inline-flex items-center justify-center rounded-xl bg-violet-700 px-6 py-3 font-semibold text-white transition hover:bg-violet-800"
                >
                    Lihat Katalog
                </a>
            </div>
        @else
            {{-- Daftar pesanan --}}
            <div class="mt-10 space-y-6">
                @foreach ($orders as $order)
                    @php
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

                        $totalQuantity = $order->items->sum('quantity');
                        $umkmCount = $order->items
                            ->pluck('umkm_id')
                            ->unique()
                            ->count();
                    @endphp

                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        {{-- Header pesanan --}}
                        <div class="flex flex-col justify-between gap-4 border-b border-slate-200 bg-slate-50 px-6 py-5 md:flex-row md:items-center">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">
                                    Nomor Pesanan
                                </p>

                                <p class="mt-1 font-mono text-lg font-bold text-slate-900">
                                    {{ $order->order_number }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full px-4 py-2 text-xs font-bold {{ $paymentStatus['class'] }}">
                                    {{ $paymentStatus['label'] }}
                                </span>

                                <span class="rounded-full px-4 py-2 text-xs font-bold {{ $orderStatus['class'] }}">
                                    {{ $orderStatus['label'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Isi pesanan --}}
                        <div class="p-6">
                            <div class="grid gap-6 md:grid-cols-4">
                                <div>
                                    <p class="text-sm text-slate-500">
                                        Tanggal Pesanan
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $order->created_at->format('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $order->created_at->format('H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-500">
                                        Jumlah Produk
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ number_format($totalQuantity) }} item
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Dari {{ number_format($umkmCount) }} UMKM
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-500">
                                        Penerima
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $order->recipient_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $order->phone }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-500">
                                        Total Pembayaran
                                    </p>

                                    <p class="mt-1 text-xl font-bold text-violet-700">
                                        Rp{{ number_format(
                                            (float) $order->total_amount,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Produk ringkas --}}
                            <div class="mt-6 border-t border-slate-100 pt-6">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($order->items->take(3) as $item)
                                        <span class="rounded-xl bg-slate-100 px-3 py-2 text-sm text-slate-600">
                                            {{ $item->product_name }}
                                            × {{ $item->quantity }}
                                        </span>
                                    @endforeach

                                    @if ($order->items->count() > 3)
                                        <span class="rounded-xl bg-violet-50 px-3 py-2 text-sm font-semibold text-violet-700">
                                            +{{ $order->items->count() - 3 }} produk lainnya
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div class="mt-6 flex flex-col justify-end gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                                @if (
                                    $order->canUploadPayment()
                                    && Route::has('customer.payment.create')
                                    && $order->order_status !== 'cancelled'
                                )
                                    <a
                                        href="{{ route('customer.payment.create', $order) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                                    >
                                        {{ $order->payment_status === 'rejected'
                                            ? 'Upload Ulang Pembayaran'
                                            : 'Upload Pembayaran' }}
                                    </a>
                                @endif

                                <a
                                    href="{{ route('customer.orders.show', $order) }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection

