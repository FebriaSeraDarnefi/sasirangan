@extends('layouts.dashboard')

@section('title', 'Daftar Customer')

@section('content')
    {{-- Header halaman --}}
    <div class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold text-violet-600">
                Administrasi Pengguna
            </p>

            <h1 class="mt-1 text-3xl font-bold text-slate-900">
                Daftar Customer
            </h1>

            <p class="mt-2 max-w-2xl text-slate-500">
                Lihat dan pantau seluruh customer yang telah
                terdaftar pada sistem SasiVerse.
            </p>
        </div>

        <a
            href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Ringkasan customer --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">
                Total Customer
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format(
                    $customerSummary['total']
                ) }}
            </p>
        </div>

        <div class="rounded-2xl border border-green-200 bg-green-50 p-6 shadow-sm">
            <p class="text-sm text-green-700">
                Customer Aktif
            </p>

            <p class="mt-2 text-3xl font-bold text-green-800">
                {{ number_format(
                    $customerSummary['active']
                ) }}
            </p>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm">
            <p class="text-sm text-red-700">
                Customer Nonaktif
            </p>

            <p class="mt-2 text-3xl font-bold text-red-800">
                {{ number_format(
                    $customerSummary['inactive']
                ) }}
            </p>
        </div>

        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm">
            <p class="text-sm text-violet-700">
                Customer Baru Bulan Ini
            </p>

            <p class="mt-2 text-3xl font-bold text-violet-800">
                {{ number_format(
                    $customerSummary['new_this_month']
                ) }}
            </p>
        </div>
    </div>

    {{-- Filter dan pencarian --}}
    <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <form
            method="GET"
            action="{{ route('admin.customers.index') }}"
            class="grid gap-4 md:grid-cols-[minmax(0,1fr)_220px_auto]"
        >
            <div>
                <label
                    for="q"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Cari Customer
                </label>

                <input
                    id="q"
                    type="search"
                    name="q"
                    value="{{ $keyword }}"
                    placeholder="Cari nama, email, telepon, atau alamat..."
                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                >
            </div>

            <div>
                <label
                    for="status"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Status Akun
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                >
                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="active"
                        @selected($status === 'active')
                    >
                        Aktif
                    </option>

                    <option
                        value="inactive"
                        @selected($status === 'inactive')
                    >
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-violet-700 px-5 py-3 font-semibold text-white transition hover:bg-violet-800"
                >
                    Terapkan
                </button>

                @if (
                    filled($keyword)
                    || filled($status)
                )
                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Informasi hasil pencarian --}}
    <div class="mt-6 flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
        <p class="text-sm text-slate-500">
            Menampilkan
            <span class="font-semibold text-slate-800">
                {{ number_format($customers->count()) }}
            </span>
            dari
            <span class="font-semibold text-slate-800">
                {{ number_format($customers->total()) }}
            </span>
            customer.
        </p>

        @if (
            filled($keyword)
            || filled($status)
        )
            <p class="text-sm font-medium text-violet-700">
                Filter sedang diterapkan
            </p>
        @endif
    </div>

    {{-- Tabel customer --}}
    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Customer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Kontak
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Alamat
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pesanan
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total Transaksi
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Tanggal Daftar
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        @php
                            $statusClass = match (
                                $customer->status
                            ) {
                                'active' =>
                                    'bg-green-100 text-green-700',

                                'inactive' =>
                                    'bg-red-100 text-red-700',

                                default =>
                                    'bg-slate-100 text-slate-700',
                            };

                            $statusLabel = match (
                                $customer->status
                            ) {
                                'active' => 'Aktif',
                                'inactive' => 'Nonaktif',
                                default => ucfirst(
                                    $customer->status
                                ),
                            };
                        @endphp

                        <tr class="transition hover:bg-slate-50">
                            {{-- Nama dan email --}}
                            <td class="whitespace-nowrap px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-violet-100 font-bold uppercase text-violet-700">
                                        {{ mb_substr(
                                            $customer->name,
                                            0,
                                            1
                                        ) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $customer->name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $customer->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Nomor telepon --}}
                            <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600">
                                @if ($customer->phone)
                                    {{ $customer->phone }}
                                @else
                                    <span class="text-slate-400">
                                        Belum diisi
                                    </span>
                                @endif
                            </td>

                            {{-- Alamat --}}
                            <td class="max-w-xs px-6 py-5">
                                @if ($customer->address)
                                    <p
                                        class="line-clamp-2 text-sm leading-6 text-slate-600"
                                        title="{{ $customer->address }}"
                                    >
                                        {{ $customer->address }}
                                    </p>
                                @else
                                    <span class="text-sm text-slate-400">
                                        Belum diisi
                                    </span>
                                @endif
                            </td>

                            {{-- Jumlah pesanan --}}
                            <td class="whitespace-nowrap px-6 py-5 text-center">
                                <span class="inline-flex min-w-10 items-center justify-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                    {{ number_format(
                                        $customer->orders_count
                                    ) }}
                                </span>
                            </td>

                            {{-- Total transaksi --}}
                            <td class="whitespace-nowrap px-6 py-5 text-right">
                                <p class="font-semibold text-slate-900">
                                    Rp{{ number_format(
                                        (float) (
                                            $customer->total_spent
                                            ?? 0
                                        ),
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </p>
                            </td>

                            {{-- Status --}}
                            <td class="whitespace-nowrap px-6 py-5 text-center">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Tanggal daftar --}}
                            <td class="whitespace-nowrap px-6 py-5">
                                <p class="text-sm font-medium text-slate-700">
                                    {{ $customer->created_at
                                        ->translatedFormat(
                                            'd M Y'
                                        ) }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $customer->created_at
                                        ->format('H:i') }}
                                    WITA
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.7"
                                        stroke="currentColor"
                                        class="h-8 w-8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M18 7.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM3.75 21a8.25 8.25 0 0 1 16.5 0"
                                        />
                                    </svg>
                                </div>

                                <p class="mt-4 font-semibold text-slate-700">
                                    Customer tidak ditemukan
                                </p>

                                <p class="mt-2 text-sm text-slate-500">
                                    Belum ada customer atau data tidak sesuai
                                    dengan filter pencarian.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($customers->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
@endsection