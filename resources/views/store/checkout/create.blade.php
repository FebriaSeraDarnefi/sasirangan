@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
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
                href="{{ route('customer.cart.index') }}"
                class="transition hover:text-violet-700"
            >
                Keranjang
            </a>

            <span>/</span>

            <span class="font-medium text-slate-800">
                Checkout
            </span>
        </div>

        {{-- Header --}}
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                Penyelesaian Pesanan
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">
                Checkout
            </h1>

            <p class="mt-3 max-w-2xl leading-7 text-slate-500">
                Pastikan informasi penerima dan produk yang akan dipesan
                sudah benar sebelum membuat pesanan.
            </p>
        </div>

        {{-- Pesan error --}}
        @if (session('error'))
            <div class="mt-7 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="mt-7 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <p class="font-bold">
                    Checkout belum dapat diproses.
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
            action="{{ route('customer.checkout.store') }}"
            class="mt-10 grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]"
        >
            @csrf

            {{-- Form data penerima --}}
            <div class="space-y-7">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
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
                                    d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                Informasi Penerima
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                Informasi ini digunakan untuk proses pengiriman
                                pesanan.
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 grid gap-6 sm:grid-cols-2">
                        {{-- Nama penerima --}}
                        <div>
                            <label
                                for="recipient_name"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Nama Penerima
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="recipient_name"
                                type="text"
                                name="recipient_name"
                                value="{{ old('recipient_name', $user->name) }}"
                                maxlength="255"
                                required
                                autocomplete="name"
                                placeholder="Masukkan nama penerima"
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                            >

                            @error('recipient_name')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Nomor telepon --}}
                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Nomor Telepon/WhatsApp
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}"
                                maxlength="30"
                                required
                                autocomplete="tel"
                                placeholder="Contoh: 081234567890"
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                            >

                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <label
                                for="address"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Alamat Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                rows="5"
                                maxlength="2000"
                                required
                                autocomplete="street-address"
                                placeholder="Masukkan jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos"
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                            >{{ old('address', $user->address ?? '') }}</textarea>

                            <p class="mt-2 text-xs leading-5 text-slate-500">
                                Tuliskan alamat selengkap mungkin agar pesanan
                                mudah dikirim.
                            </p>

                            @error('address')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="sm:col-span-2">
                            <label
                                for="notes"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Catatan Pesanan
                                <span class="font-normal text-slate-400">
                                    (opsional)
                                </span>
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                rows="4"
                                maxlength="2000"
                                placeholder="Contoh: Hubungi sebelum mengirim, warna harus sesuai foto, atau catatan lainnya"
                                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-violet-500 focus:ring-violet-500"
                            >{{ old('notes') }}</textarea>

                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Daftar produk --}}
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">
                                Produk yang Dipesan
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ number_format($cart->items->sum('quantity')) }}
                                item dari
                                {{ number_format($groupedItems->count()) }}
                                UMKM.
                            </p>
                        </div>

                        <a
                            href="{{ route('customer.cart.index') }}"
                            class="text-sm font-semibold text-violet-700 transition hover:underline"
                        >
                            Ubah Keranjang
                        </a>
                    </div>

                    <div class="mt-7 space-y-7">
                        @foreach ($groupedItems as $items)
                            @php
                                $firstItem = $items->first();
                                $umkm = $firstItem->product->umkm;

                                $umkmSubtotal = $items->sum(function ($item) {
                                    return (float) $item->price
                                        * $item->quantity;
                                });
                            @endphp

                            <div class="overflow-hidden rounded-2xl border border-slate-200">
                                {{-- Nama UMKM --}}
                                <div class="flex items-center justify-between gap-4 bg-slate-50 px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($umkm->logo)
                                            <img
                                                src="{{ asset('storage/'.$umkm->logo) }}"
                                                alt="{{ $umkm->business_name }}"
                                                class="h-10 w-10 rounded-xl border border-slate-200 object-cover"
                                            >
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 font-bold text-violet-700">
                                                {{ strtoupper(substr($umkm->business_name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">
                                                UMKM
                                            </p>

                                            <p class="font-bold text-slate-900">
                                                {{ $umkm->business_name }}
                                            </p>
                                        </div>
                                    </div>

                                    <p class="text-sm font-bold text-violet-700">
                                        Rp{{ number_format(
                                            $umkmSubtotal,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </p>
                                </div>

                                {{-- Produk --}}
                                <div class="divide-y divide-slate-100">
                                    @foreach ($items as $item)
                                        @php
                                            $product = $item->product;

                                            $itemSubtotal = (
                                                (float) $item->price
                                                * $item->quantity
                                            );
                                        @endphp

                                        <div class="flex gap-4 p-5">
                                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                                @if ($product->main_image)
                                                    <img
                                                        src="{{ asset('storage/'.$product->main_image) }}"
                                                        alt="{{ $product->name }}"
                                                        class="h-full w-full object-cover"
                                                    >
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-xl font-bold text-violet-700">
                                                        {{ strtoupper(substr($product->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-col justify-between gap-2 sm:flex-row">
                                                    <div>
                                                        <p class="font-bold text-slate-900">
                                                            {{ $product->name }}
                                                        </p>

                                                        <p class="mt-1 text-sm text-slate-500">
                                                            {{ $product->motif_name ?: 'Produk Sasirangan' }}
                                                        </p>

                                                        <p class="mt-2 text-sm text-slate-600">
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
                                                            $itemSubtotal,
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ringkasan checkout --}}
            <aside>
                <div class="sticky top-28 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900">
                        Ringkasan Pembayaran
                    </h2>

                    <div class="mt-6 space-y-4 border-b border-slate-200 pb-6">
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-slate-500">
                                Subtotal produk
                            </span>

                            <span class="font-semibold text-slate-900">
                                Rp{{ number_format(
                                    (float) $subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-slate-500">
                                Ongkos kirim
                            </span>

                            <span class="font-semibold text-slate-900">
                                @if ((float) $shippingCost > 0)
                                    Rp{{ number_format(
                                        (float) $shippingCost,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                @else
                                    Gratis
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-slate-500">
                                Total item
                            </span>

                            <span class="font-semibold text-slate-900">
                                {{ number_format($cart->items->sum('quantity')) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-end justify-between gap-4">
                        <span class="font-semibold text-slate-600">
                            Total Pembayaran
                        </span>

                        <span class="text-right text-2xl font-bold text-violet-700">
                            Rp{{ number_format(
                                (float) $totalAmount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </span>
                    </div>

                    <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-900">
                            Pembayaran belum dilakukan
                        </p>

                        <p class="mt-1 text-xs leading-5 text-amber-700">
                            Setelah pesanan dibuat, kamu akan diarahkan ke
                            halaman detail untuk mengunggah bukti pembayaran.
                        </p>
                    </div>

                    <button
                        type="submit"
                        onclick="return confirm('Pastikan data penerima dan pesanan sudah benar. Buat pesanan sekarang?')"
                        class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-violet-700 px-5 py-4 font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-800"
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
                                d="m9 12.75 2.25 2.25L15 9.75"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />
                        </svg>

                        Buat Pesanan
                    </button>

                    <a
                        href="{{ route('customer.cart.index') }}"
                        class="mt-3 flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Kembali ke Keranjang
                    </a>

                    <p class="mt-4 text-center text-xs leading-5 text-slate-400">
                        Harga dan stok akan diperiksa kembali ketika pesanan
                        dibuat.
                    </p>
                </div>
            </aside>
        </form>
    </section>
@endsection
