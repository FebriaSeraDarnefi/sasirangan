@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

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
                'label' => 'Diterima',
                'class' => 'bg-green-100 text-green-700',
            ],
            'rejected' => [
                'label' => 'Ditolak',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-sm font-medium text-violet-600">
            Dashboard Admin
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Selamat datang, {{ auth()->user()->name }}
        </h1>

        <p class="mt-2 text-slate-500">
            Berikut ringkasan aktivitas aplikasi SasiVerse.
        </p>

        {{-- Menu cepat --}}
        <div class="mt-6 flex flex-wrap gap-3">
            @if (Route::has('admin.umkms.index'))
                <a
                    href="{{ route('admin.umkms.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-violet-200 bg-violet-50 px-5 py-3 text-sm font-semibold text-violet-700 transition hover:bg-violet-100"
                >
                    Verifikasi UMKM
                </a>
            @endif

            @if (Route::has('admin.payments.index'))
                <a
                    href="{{ route('admin.payments.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                >
                    Verifikasi Pembayaran
                </a>
            @endif
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

    {{-- Statistik --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">
                Jumlah Customer
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['customers'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">
                Jumlah UMKM
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['umkms'] ?? 0) }}
            </p>
        </div>

        @if (Route::has('admin.umkms.index'))
            <a
                href="{{ route('admin.umkms.index') }}"
                class="block rounded-2xl border border-yellow-200 bg-yellow-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
            >
                <p class="text-sm text-yellow-700">
                    UMKM Menunggu Verifikasi
                </p>

                <p class="mt-2 text-3xl font-bold text-yellow-800">
                    {{ number_format($statistics['pending_umkms'] ?? 0) }}
                </p>

                <p class="mt-3 text-sm font-semibold text-yellow-700">
                    Periksa UMKM →
                </p>
            </a>
        @else
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6 shadow-sm">
                <p class="text-sm text-yellow-700">
                    UMKM Menunggu Verifikasi
                </p>

                <p class="mt-2 text-3xl font-bold text-yellow-800">
                    {{ number_format($statistics['pending_umkms'] ?? 0) }}
                </p>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">
                Jumlah Produk
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['products'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">
                Jumlah Pesanan
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['orders'] ?? 0) }}
            </p>
        </div>

        @if (Route::has('admin.payments.index'))
            <a
                href="{{ route('admin.payments.index', ['status' => 'waiting']) }}"
                class="block rounded-2xl border border-orange-200 bg-orange-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
            >
                <p class="text-sm text-orange-700">
                    Pembayaran Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-orange-800">
                    {{ number_format($statistics['waiting_payments'] ?? 0) }}
                </p>

                <p class="mt-3 text-sm font-semibold text-orange-700">
                    Periksa pembayaran →
                </p>
            </a>
        @else
            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-6 shadow-sm">
                <p class="text-sm text-orange-700">
                    Pembayaran Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-orange-800">
                    {{ number_format($statistics['waiting_payments'] ?? 0) }}
                </p>
            </div>
        @endif
    </div>

    {{-- Pesanan terbaru --}}
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col justify-between gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Pesanan Terbaru
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pantau pesanan dan pembayaran customer terbaru.
                </p>
            </div>

            @if (Route::has('admin.payments.index'))
                <a
                    href="{{ route('admin.payments.index') }}"
                    class="text-sm font-semibold text-violet-700 transition hover:underline"
                >
                    Lihat semua pembayaran →
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
                            Customer
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pembayaran
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($latestOrders as $order)
                        @php
                            $paymentStatus = $paymentStatuses[$order->payment_status]
                                ?? [
                                    'label' => ucfirst($order->payment_status),
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

                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $order->user?->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $order->user?->email ?? '-' }}
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

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                @if (
                                    $order->payment
                                    && Route::has('admin.payments.show')
                                )
                                    <a
                                        href="{{ route(
                                            'admin.payments.show',
                                            $order->payment
                                        ) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-800"
                                    >
                                        {{ $order->payment_status === 'waiting'
                                            ? 'Periksa Pembayaran'
                                            : 'Lihat Pembayaran' }}
                                    </a>
                                @elseif ($order->payment_status === 'unpaid')
                                    <span class="inline-flex rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500">
                                        Belum Upload
                                    </span>
                                @else
                                    <span class="inline-flex rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500">
                                        Tidak Tersedia
                                    </span>
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
                                    Belum ada pesanan
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Pesanan customer akan tampil di sini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

