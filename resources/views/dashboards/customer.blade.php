@extends('layouts.dashboard')

@section('title', 'Dashboard Customer')

@section('content')
    {{-- Header dashboard --}}
    <div class="mb-8">
        <p class="text-sm font-medium text-violet-600">
            Akun Customer
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Halo, {{ auth()->user()->name }}
        </h1>

        <p class="mt-2 text-slate-500">
            Pantau aktivitas dan pesanan Sasirangan Anda.
        </p>

        {{-- Menu tindakan Customer --}}
        <div class="mt-6 flex flex-wrap gap-3">
            {{-- Beranda toko --}}
            <a
                href="{{ route('store.home') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700"
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
                        d="M3 10.5 12 3l9 7.5v9a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5v-9Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 21v-6h6v6"
                    />
                </svg>

                Beranda Toko
            </a>

            {{-- Katalog --}}
            <a
                href="{{ route('store.catalog') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-800"
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
                        d="M3.75 6.75h16.5v13.5H3.75V6.75Zm3-3h10.5v3H6.75v-3Z"
                    />
                </svg>

                Belanja Produk
            </a>

            {{-- Keranjang --}}
            @if (Route::has('customer.cart.index'))
                <a
                    href="{{ route('customer.cart.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-100"
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
                            d="M3 3h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H6"
                        />

                        <circle cx="10" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                    </svg>

                    Keranjang Belanja
                </a>
            @endif

            {{-- Pesanan Saya --}}
            @if (Route::has('customer.orders.index'))
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-5 py-3 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100"
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
                            d="M6 7.5h12l1 13H5l1-13Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 9V6a3 3 0 0 1 6 0v3"
                        />
                    </svg>

                    Pesanan Saya
                </a>
            @endif

            {{-- Keluar --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                onsubmit="return confirm('Yakin ingin keluar dari akun?')"
            >
                @csrf

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100"
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
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m18 15 3-3m0 0-3-3m3 3H9"
                        />
                    </svg>

                    Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan gagal --}}
    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">
                Semua Pesanan
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['orders'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6 shadow-sm">
            <p class="text-sm text-yellow-700">
                Pesanan Berjalan
            </p>

            <p class="mt-2 text-3xl font-bold text-yellow-800">
                {{ number_format($statistics['pending_orders'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-2xl border border-green-200 bg-green-50 p-6 shadow-sm">
            <p class="text-sm text-green-700">
                Pesanan Selesai
            </p>

            <p class="mt-2 text-3xl font-bold text-green-800">
                {{ number_format($statistics['completed_orders'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm">
            <p class="text-sm text-violet-700">
                Total Belanja
            </p>

            <p class="mt-2 text-xl font-bold text-violet-800">
                Rp{{ number_format(
                    (float) ($statistics['total_spent'] ?? 0),
                    0,
                    ',',
                    '.'
                ) }}
            </p>
        </div>
    </div>

    {{-- Pesanan terbaru --}}
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Pesanan Terbaru
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Daftar aktivitas pesanan terbaru Anda.
                </p>
            </div>

            @if (Route::has('customer.orders.index'))
                <a
                    href="{{ route('customer.orders.index') }}"
                    class="text-sm font-semibold text-violet-700 transition hover:underline"
                >
                    Lihat Semua Pesanan →
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Nomor
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pembayaran
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pesanan
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($latestOrders as $order)
                        @php
                            $paymentLabels = [
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
                                    'label' => 'Ditolak',
                                    'class' => 'bg-red-100 text-red-700',
                                ],
                            ];

                            $orderLabels = [
                                'pending' => [
                                    'label' => 'Menunggu',
                                    'class' => 'bg-amber-100 text-amber-700',
                                ],
                                'processing' => [
                                    'label' => 'Diproses',
                                    'class' => 'bg-blue-100 text-blue-700',
                                ],
                                'packed' => [
                                    'label' => 'Dikemas',
                                    'class' => 'bg-indigo-100 text-indigo-700',
                                ],
                                'shipped' => [
                                    'label' => 'Dikirim',
                                    'class' => 'bg-violet-100 text-violet-700',
                                ],
                                'completed' => [
                                    'label' => 'Selesai',
                                    'class' => 'bg-green-100 text-green-700',
                                ],
                                'cancelled' => [
                                    'label' => 'Dibatalkan',
                                    'class' => 'bg-red-100 text-red-700',
                                ],
                            ];

                            $paymentStatus = $paymentLabels[$order->payment_status]
                                ?? [
                                    'label' => ucfirst($order->payment_status),
                                    'class' => 'bg-slate-100 text-slate-700',
                                ];

                            $orderStatus = $orderLabels[$order->order_status]
                                ?? [
                                    'label' => ucfirst($order->order_status),
                                    'class' => 'bg-slate-100 text-slate-700',
                                ];
                        @endphp

                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <p class="font-mono text-sm font-semibold text-slate-900">
                                    {{ $order->order_number }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-700">
                                Rp{{ number_format(
                                    (float) $order->total_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold {{ $paymentStatus['class'] }}">
                                    {{ $paymentStatus['label'] }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold {{ $orderStatus['class'] }}">
                                    {{ $orderStatus['label'] }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                @if (Route::has('customer.orders.show'))
                                    <a
                                        href="{{ route('customer.orders.show', $order) }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700"
                                    >
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >
                                <p class="text-sm font-semibold text-slate-700">
                                    Anda belum mempunyai pesanan
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Produk yang selesai di-checkout akan muncul di sini.
                                </p>

                                <a
                                    href="{{ route('store.catalog') }}"
                                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                                >
                                    Mulai Belanja
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
