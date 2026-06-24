
@extends('layouts.dashboard')

@section('title', 'Detail Pesanan UMKM')

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
                'label' => 'Pesanan Selesai',
                'class' => 'bg-green-100 text-green-700',
            ],
            'cancelled' => [
                'label' => 'Pesanan Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

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

        $orderStatus = $orderStatuses[$order->order_status]
            ?? [
                'label' => ucfirst($order->order_status),
                'class' => 'bg-slate-100 text-slate-700',
            ];

        $paymentStatus = $paymentStatuses[$order->payment_status]
            ?? [
                'label' => ucfirst($order->payment_status),
                'class' => 'bg-slate-100 text-slate-700',
            ];
    @endphp

    {{-- Header --}}
    <div class="mb-8 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                {{ $umkm->business_name }}
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Detail Pesanan
            </h1>

            <p class="mt-2 text-slate-500">
                Informasi produk UMKM Anda yang dipesan oleh customer.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('umkm.orders.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Kembali ke Pesanan
            </a>

            <a
                href="{{ route('umkm.dashboard') }}"
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
@if ($errors->any()) <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700"> <p class="font-bold">
Data belum lengkap: </p>

    <ul class="mt-2 list-inside list-disc space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

@endif

    {{-- Ringkasan --}}
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
                Status Pesanan
            </p>

          @php
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
'label' => 'Sudah Dikirim',
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


$fulfillmentStatus = $fulfillmentStatuses[$fulfillment->status]
    ?? [
        'label' => ucfirst($fulfillment->status),
        'class' => 'bg-slate-100 text-slate-700',
    ];


@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <p class="text-sm text-slate-500">
        Status Produk Anda
    </p>


<span class="mt-3 inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $fulfillmentStatus['class'] }}">
    {{ $fulfillmentStatus['label'] }}
</span>


</div>

        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">
                Status Pembayaran
            </p>

            <span class="mt-3 inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $paymentStatus['class'] }}">
                {{ $paymentStatus['label'] }}
            </span>
        </div>

        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5 shadow-sm">
            <p class="text-sm text-violet-700">
                Subtotal Produk Anda
            </p>

            <p class="mt-2 text-2xl font-bold text-violet-800">
                Rp{{ number_format(
                    (float) $umkmSubtotal,
                    0,
                    ',',
                    '.'
                ) }}
            </p>

            <p class="mt-2 text-xs text-violet-600">
                {{ number_format($totalItems) }} item
            </p>
        </div>
    </div>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_380px]">
        {{-- Kolom utama --}}
        <div class="space-y-7">
            {{-- Produk UMKM --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
                    <h2 class="text-xl font-bold text-slate-900">
                        Produk yang Dipesan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Hanya produk milik {{ $umkm->business_name }} yang
                        ditampilkan di halaman ini.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Produk
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Harga
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Jumlah
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-900">
                                            {{ $item->product_name }}
                                        </p>

                                        @if ($item->product)
                                            <p class="mt-1 text-xs text-slate-400">
                                                ID Produk:
                                                {{ $item->product->id }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600">
                                        Rp{{ number_format(
                                            (float) $item->price,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-center">
                                        <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-xl bg-slate-100 px-3 text-sm font-bold text-slate-700">
                                            {{ number_format($item->quantity) }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-right font-bold text-slate-900">
                                        Rp{{ number_format(
                                            (float) $item->subtotal,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="4"
                                        class="px-6 py-12 text-center text-sm text-slate-500"
                                    >
                                        Produk pesanan tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 sm:px-8">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p class="text-sm text-slate-500">
                                Total produk UMKM
                            </p>

                            <p class="mt-1 font-bold text-slate-900">
                                {{ number_format($totalItems) }} item
                            </p>
                        </div>

                        <div class="sm:text-right">
                            <p class="text-sm text-slate-500">
                                Subtotal UMKM
                            </p>

                            <p class="mt-1 text-2xl font-bold text-violet-700">
                                Rp{{ number_format(
                                    (float) $umkmSubtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi pengiriman --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                        Tujuan Pengiriman
                    </p>

                    <h2 class="mt-2 text-xl font-bold text-slate-900">
                        Informasi Penerima
                    </h2>
                </div>

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

                        <dd class="mt-1">
                            <a
                                href="tel:{{ $order->phone }}"
                                class="font-semibold text-violet-700 hover:underline"
                            >
                                {{ $order->phone }}
                            </a>
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
                                Catatan Customer
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
            {{-- Customer --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Data Customer
                </h2>

                <dl class="mt-6 space-y-5">
                    <div>
                        <dt class="text-sm text-slate-500">
                            Nama Akun
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $order->user?->name ?? $order->recipient_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Email
                        </dt>

                        <dd class="mt-1 break-all text-sm font-semibold text-slate-900">
                            {{ $order->user?->email ?? '-' }}
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
                </dl>
            </div>

            {{-- Pembayaran --}}
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
                    Pembayaran Sudah Diterima
                </h2>

                <p class="mt-2 text-sm leading-6 text-green-700">
                    Admin SasiVerse sudah memverifikasi pembayaran customer.
                    Produk dapat mulai disiapkan.
                </p>

                @if ($order->payment?->verified_at)
                    <p class="mt-4 text-xs font-semibold text-green-700">
                        Diverifikasi:
                        {{ $order->payment->verified_at->format('d M Y, H:i') }}
                    </p>
                @endif
            </div>

            

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <h2 class="text-lg font-bold text-slate-900">
        Proses Pesanan
    </h2>


<p class="mt-2 text-sm leading-6 text-slate-500">
    Perbarui status produk milik UMKM Anda sesuai proses pengerjaan.
</p>

@if ($fulfillment->status === 'processing')
    <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <p class="font-bold text-blue-900">
            Sedang Diproses
        </p>

        <p class="mt-2 text-sm leading-6 text-blue-700">
            Siapkan produk sesuai jumlah pesanan. Setelah produk selesai
            dikemas, tekan tombol di bawah.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('umkm.orders.packed', $order) }}"
        class="mt-5"
        onsubmit="return confirm('Apakah produk sudah selesai dikemas?')"
    >
        @csrf
        @method('PATCH')

        <button
            type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-4 font-semibold text-white transition hover:bg-indigo-700"
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
                    d="M21 8.25 12 3 3 8.25m18 0L12 13.5 3 8.25m18 0v7.5L12 21m0-7.5v7.5m0-7.5L3 8.25v7.5L12 21"
                />
            </svg>

            Tandai Sudah Dikemas
        </button>
    </form>
@elseif ($fulfillment->status === 'packed')
    <div class="mt-6 rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
        <p class="font-bold text-indigo-900">
            Produk Sudah Dikemas
        </p>

        <p class="mt-2 text-sm leading-6 text-indigo-700">
            Masukkan informasi kurir dan nomor resi sebelum menandai
            pesanan sudah dikirim.
        </p>

        @if ($fulfillment->packed_at)
            <p class="mt-3 text-xs font-semibold text-indigo-700">
                Dikemas pada
                {{ $fulfillment->packed_at->format('d M Y, H:i') }}
            </p>
        @endif
    </div>

    <form
        method="POST"
        action="{{ route('umkm.orders.shipped', $order) }}"
        class="mt-6 space-y-5"
        onsubmit="return confirm('Apakah pesanan sudah diserahkan kepada kurir?')"
    >
        @csrf
        @method('PATCH')

        <div>
            <label
                for="courier"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Nama Kurir
                <span class="text-red-500">*</span>
            </label>

            <input
                id="courier"
                name="courier"
                type="text"
                maxlength="100"
                required
                value="{{ old('courier', $fulfillment->courier) }}"
                placeholder="Contoh: JNE, J&T, SiCepat"
                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
            >

            @error('courier')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label
                for="tracking_number"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Nomor Resi
                <span class="text-red-500">*</span>
            </label>

            <input
                id="tracking_number"
                name="tracking_number"
                type="text"
                maxlength="150"
                required
                value="{{ old(
                    'tracking_number',
                    $fulfillment->tracking_number
                ) }}"
                placeholder="Masukkan nomor resi pengiriman"
                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
            >

            @error('tracking_number')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label
                for="notes"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Catatan Pengiriman
            </label>

            <textarea
                id="notes"
                name="notes"
                rows="4"
                maxlength="1000"
                placeholder="Catatan tambahan untuk customer"
                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
            >{{ old('notes', $fulfillment->notes) }}</textarea>

            @error('notes')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button
            type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-violet-700 px-5 py-4 font-semibold text-white transition hover:bg-violet-800"
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
                    d="M8.25 18.75a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h7.5m3 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h.75a.75.75 0 0 0 .75-.75v-5.25m-16.5 6h-.75A.75.75 0 0 1 2.25 18V6.75A.75.75 0 0 1 3 6h9.75v12.75m4.5-12h-4.5m4.5 0 4.5 4.5m-4.5-4.5v4.5h4.5"
                />
            </svg>

            Tandai Sudah Dikirim
        </button>
    </form>
@elseif ($fulfillment->status === 'shipped')
    <div class="mt-6 rounded-2xl border border-violet-200 bg-violet-50 p-5">
        <p class="font-bold text-violet-900">
            Pesanan Sudah Dikirim
        </p>

        <p class="mt-2 text-sm leading-6 text-violet-700">
            Pesanan sedang dalam perjalanan dan menunggu konfirmasi
            penerimaan dari customer.
        </p>
    </div>

    <dl class="mt-6 space-y-5">
        <div>
            <dt class="text-sm text-slate-500">
                Kurir
            </dt>

            <dd class="mt-1 font-bold text-slate-900">
                {{ $fulfillment->courier ?? '-' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm text-slate-500">
                Nomor Resi
            </dt>

            <dd class="mt-1 break-all font-mono font-bold text-violet-700">
                {{ $fulfillment->tracking_number ?? '-' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm text-slate-500">
                Waktu Pengiriman
            </dt>

            <dd class="mt-1 font-semibold text-slate-900">
                {{ $fulfillment->shipped_at?->format('d M Y, H:i') ?? '-' }}
            </dd>
        </div>

        @if ($fulfillment->notes)
            <div>
                <dt class="text-sm text-slate-500">
                    Catatan Pengiriman
                </dt>

                <dd class="mt-2 whitespace-pre-line rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                    {{ $fulfillment->notes }}
                </dd>
            </div>
        @endif
    </dl>

    <div class="mt-6 rounded-xl bg-slate-100 p-4 text-sm leading-6 text-slate-600">
        Status selesai akan diberikan setelah customer mengonfirmasi bahwa
        pesanan sudah diterima.
    </div>
@elseif ($fulfillment->status === 'completed')
    <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 p-5">
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

        <p class="mt-4 font-bold text-green-900">
            Pesanan Selesai
        </p>

        <p class="mt-2 text-sm leading-6 text-green-700">
            Customer telah mengonfirmasi bahwa pesanan sudah diterima.
        </p>

        @if ($fulfillment->completed_at)
            <p class="mt-3 text-xs font-semibold text-green-700">
                Selesai pada
                {{ $fulfillment->completed_at->format('d M Y, H:i') }}
            </p>
        @endif
    </div>
@elseif ($fulfillment->status === 'cancelled')
    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5">
        <p class="font-bold text-red-900">
            Pesanan Dibatalkan
        </p>

        <p class="mt-2 text-sm leading-6 text-red-700">
            Pemenuhan pesanan ini telah dibatalkan dan tidak dapat
            diproses lebih lanjut.
        </p>
    </div>
@endif

</div>

        </aside>
    </div>
@endsection

