@extends('layouts.dashboard')

@section('title', 'Pesanan Masuk')

@section('content')
    @php
        $orderStatuses = [
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
                'label' => 'Selesai',
                'class' => 'bg-green-100 text-green-700',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-8 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                {{ $umkm->business_name }}
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Pesanan Masuk
            </h1>

            <p class="mt-2 max-w-2xl leading-7 text-slate-500">
                Lihat pesanan customer yang sudah dibayar dan berisi produk
                dari UMKM Anda.
            </p>
        </div>

        <a
            href="{{ route('umkm.dashboard') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
        <a
            href="{{ route('umkm.orders.index') }}"
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-slate-500">
                Semua Pesanan
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['all'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('umkm.orders.index', ['status' => 'processing']) }}"
            class="rounded-2xl border border-blue-200 bg-blue-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-blue-700">
                Sedang Diproses
            </p>

            <p class="mt-2 text-3xl font-bold text-blue-800">
                {{ number_format($statistics['processing'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('umkm.orders.index', ['status' => 'packed']) }}"
            class="rounded-2xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-indigo-700">
                Sudah Dikemas
            </p>

            <p class="mt-2 text-3xl font-bold text-indigo-800">
                {{ number_format($statistics['packed'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('umkm.orders.index', ['status' => 'shipped']) }}"
            class="rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-violet-700">
                Sedang Dikirim
            </p>

            <p class="mt-2 text-3xl font-bold text-violet-800">
                {{ number_format($statistics['shipped'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('umkm.orders.index', ['status' => 'completed']) }}"
            class="rounded-2xl border border-green-200 bg-green-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-green-700">
                Selesai
            </p>

            <p class="mt-2 text-3xl font-bold text-green-800">
                {{ number_format($statistics['completed'] ?? 0) }}
            </p>
        </a>
    </div>

    {{-- Filter --}}
    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-bold text-slate-900">
                    Daftar Pesanan
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Filter pesanan berdasarkan status proses.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('umkm.orders.index') }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === ''
                            ? 'bg-violet-700 text-white'
                            : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                >
                    Semua
                </a>

                <a
                    href="{{ route('umkm.orders.index', ['status' => 'processing']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'processing'
                            ? 'bg-blue-600 text-white'
                            : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}"
                >
                    Diproses
                </a>

                <a
                    href="{{ route('umkm.orders.index', ['status' => 'packed']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'packed'
                            ? 'bg-indigo-600 text-white'
                            : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}"
                >
                    Dikemas
                </a>

                <a
                    href="{{ route('umkm.orders.index', ['status' => 'shipped']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'shipped'
                            ? 'bg-violet-600 text-white'
                            : 'bg-violet-50 text-violet-700 hover:bg-violet-100' }}"
                >
                    Dikirim
                </a>

                <a
                    href="{{ route('umkm.orders.index', ['status' => 'completed']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'completed'
                            ? 'bg-green-600 text-white'
                            : 'bg-green-50 text-green-700 hover:bg-green-100' }}"
                >
                    Selesai
                </a>
            </div>
        </div>
    </div>

    {{-- Daftar pesanan --}}
    @if ($orders->isEmpty())
        <div class="mt-6 rounded-3xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
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
                        d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.064v6.75a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25v-6.75c0-.936.616-1.78 1.5-2.064m16.5 0A2.25 2.25 0 0 0 18.75 6.45H5.25A2.25 2.25 0 0 0 3.75 8.511m16.5 0-7.5 4.875a1.5 1.5 0 0 1-1.5 0L3.75 8.511"
                    />
                </svg>
            </div>

            <h2 class="mt-6 text-2xl font-bold text-slate-900">
                Belum ada pesanan
            </h2>

            <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-500">
                Pesanan akan tampil setelah pembayaran customer diterima
                oleh Admin.
            </p>
        </div>
    @else
        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Pesanan
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Customer
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Produk Anda
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Subtotal UMKM
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($orders as $order)
                            @php
                                $orderStatus = $orderStatuses[$order->order_status]
                                    ?? [
                                        'label' => ucfirst($order->order_status),
                                        'class' => 'bg-slate-100 text-slate-700',
                                    ];

                                $umkmSubtotal = $order->items->sum(
                                    fn ($item) => (float) $item->subtotal
                                );

                                $totalItems = $order->items->sum('quantity');
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-5">
                                    <p class="font-mono text-sm font-bold text-slate-900">
                                        {{ $order->order_number }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">
                                        {{ $order->recipient_name }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $order->phone }}
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">
                                        {{ number_format($totalItems) }} item
                                    </p>

                                    <div class="mt-2 space-y-1">
                                        @foreach ($order->items->take(2) as $item)
                                            <p class="text-sm text-slate-500">
                                                {{ $item->product_name }}
                                                × {{ $item->quantity }}
                                            </p>
                                        @endforeach

                                        @if ($order->items->count() > 2)
                                            <p class="text-xs font-semibold text-violet-700">
                                                +{{ $order->items->count() - 2 }}
                                                produk lainnya
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-5">
                                    <p class="font-bold text-violet-700">
                                        Rp{{ number_format(
                                            $umkmSubtotal,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold {{ $orderStatus['class'] }}">
                                        {{ $orderStatus['label'] }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <a
                                        href="{{ route('umkm.orders.show', $order) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-800"
                                    >
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
@endsection

