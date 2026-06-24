@extends('layouts.dashboard')

@section('title', 'Verifikasi Pembayaran')

@section('content')
    @php
        $statusLabels = [
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
    <div class="mb-8 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                Administrasi
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Verifikasi Pembayaran
            </h1>

            <p class="mt-2 max-w-2xl leading-7 text-slate-500">
                Periksa bukti pembayaran customer, lalu terima atau tolak
                pembayaran berdasarkan data transfer yang dikirim.
            </p>
        </div>

        <a
            href="{{ route('admin.dashboard') }}"
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

    {{-- Pesan gagal --}}
    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistik pembayaran --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
        <a
            href="{{ route('admin.payments.index') }}"
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-slate-500">
                Semua Pembayaran
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['all'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('admin.payments.index', ['status' => 'waiting']) }}"
            class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-amber-700">
                Menunggu Verifikasi
            </p>

            <p class="mt-2 text-3xl font-bold text-amber-800">
                {{ number_format($statistics['waiting'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('admin.payments.index', ['status' => 'paid']) }}"
            class="rounded-2xl border border-green-200 bg-green-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-green-700">
                Pembayaran Diterima
            </p>

            <p class="mt-2 text-3xl font-bold text-green-800">
                {{ number_format($statistics['paid'] ?? 0) }}
            </p>
        </a>

        <a
            href="{{ route('admin.payments.index', ['status' => 'rejected']) }}"
            class="rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
        >
            <p class="text-sm text-red-700">
                Pembayaran Ditolak
            </p>

            <p class="mt-2 text-3xl font-bold text-red-800">
                {{ number_format($statistics['rejected'] ?? 0) }}
            </p>
        </a>

        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm">
            <p class="text-sm text-violet-700">
                Total Pembayaran Diterima
            </p>

            <p class="mt-2 text-xl font-bold text-violet-800">
                Rp{{ number_format(
                    (float) ($statistics['paid_amount'] ?? 0),
                    0,
                    ',',
                    '.'
                ) }}
            </p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="font-bold text-slate-900">
                    Daftar Pembayaran
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Filter pembayaran berdasarkan status verifikasi.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('admin.payments.index') }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === ''
                            ? 'bg-violet-700 text-white'
                            : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                >
                    Semua
                </a>

                <a
                    href="{{ route('admin.payments.index', ['status' => 'waiting']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'waiting'
                            ? 'bg-amber-600 text-white'
                            : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}"
                >
                    Menunggu
                </a>

                <a
                    href="{{ route('admin.payments.index', ['status' => 'paid']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'paid'
                            ? 'bg-green-600 text-white'
                            : 'bg-green-50 text-green-700 hover:bg-green-100' }}"
                >
                    Diterima
                </a>

                <a
                    href="{{ route('admin.payments.index', ['status' => 'rejected']) }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition
                        {{ $status === 'rejected'
                            ? 'bg-red-600 text-white'
                            : 'bg-red-50 text-red-700 hover:bg-red-100' }}"
                >
                    Ditolak
                </a>
            </div>
        </div>
    </div>

    {{-- Daftar pembayaran --}}
    @if ($payments->isEmpty())
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
                        d="M2.25 8.25h19.5m-18 0v9A2.25 2.25 0 0 0 6 19.5h12a2.25 2.25 0 0 0 2.25-2.25v-9m-18 0 2.2-3.3A2.25 2.25 0 0 1 6.32 3.75h11.36a2.25 2.25 0 0 1 1.87 1.2l2.2 3.3"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 13.5h6"
                    />
                </svg>
            </div>

            <h2 class="mt-6 text-2xl font-bold text-slate-900">
                Belum ada pembayaran
            </h2>

            <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-500">
                Belum terdapat pembayaran yang sesuai dengan filter yang
                dipilih.
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
                                Data Transfer
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Jumlah
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
                        @foreach ($payments as $payment)
                            @php
                                $paymentStatus = $statusLabels[$payment->status]
                                    ?? [
                                        'label' => ucfirst($payment->status),
                                        'class' => 'bg-slate-100 text-slate-700',
                                    ];
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                {{-- Pesanan --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <p class="font-mono text-sm font-bold text-slate-900">
                                        {{ $payment->order?->order_number ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        Dikirim
                                        {{ $payment->created_at->format('d M Y, H:i') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ number_format(
                                            $payment->order?->items?->sum('quantity') ?? 0
                                        ) }}
                                        item
                                    </p>
                                </td>

                                {{-- Customer --}}
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">
                                        {{ $payment->user?->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $payment->user?->email ?? '-' }}
                                    </p>
                                </td>

                                {{-- Data transfer --}}
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">
                                        {{ $payment->account_holder_name }}
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Transfer:
                                        {{ $payment->transfer_date?->format('d M Y') ?? '-' }}
                                    </p>
                                </td>

                                {{-- Jumlah --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <p class="font-bold text-violet-700">
                                        Rp{{ number_format(
                                            (float) $payment->amount,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </p>

                                    @if (
                                        $payment->order
                                        && (float) $payment->amount !== (float) $payment->order->total_amount
                                    )
                                        <p class="mt-1 text-xs font-semibold text-red-600">
                                            Nominal berbeda
                                        </p>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-bold {{ $paymentStatus['class'] }}">
                                        {{ $paymentStatus['label'] }}
                                    </span>

                                    @if ($payment->verified_at)
                                        <p class="mt-2 text-xs text-slate-400">
                                            {{ $payment->verified_at->format('d M Y, H:i') }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <a
                                        href="{{ route('admin.payments.show', $payment) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-violet-200 bg-violet-50 px-4 py-2.5 text-sm font-semibold text-violet-700 transition hover:bg-violet-100"
                                    >
                                        Periksa Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($payments->hasPages())
            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @endif
    @endif
@endsection

