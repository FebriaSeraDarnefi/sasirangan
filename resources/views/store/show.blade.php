
@extends('layouts.store')

@section('title', $product->name)

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
                href="{{ route('store.catalog') }}"
                class="transition hover:text-violet-700"
            >
                Katalog
            </a>

            <span>/</span>

            <span class="font-medium text-slate-800">
                {{ $product->name }}
            </span>
        </div>

        <div class="grid gap-10 lg:grid-cols-2">
            {{-- Gambar Produk --}}
            <div>
                <div class="aspect-square overflow-hidden rounded-[2rem] border border-slate-200 bg-gradient-to-br from-violet-100 via-fuchsia-50 to-amber-50 shadow-sm">
                    @if ($product->main_image)
                        <img
                            src="{{ asset('storage/'.$product->main_image) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="flex h-full flex-col items-center justify-center text-violet-700">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.2"
                                stroke="currentColor"
                                class="h-24 w-24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 4h16v16H4V4Zm0 5h16M9 4v16m6-16v16M4 15h16"
                                />
                            </svg>

                            <p class="mt-5 font-semibold">
                                Foto produk belum tersedia
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Foto Tambahan --}}
                @if ($product->images->isNotEmpty())
                    <div class="mt-4 grid grid-cols-3 gap-3 sm:grid-cols-4">
                        @foreach ($product->images as $image)
                            <a
                                href="{{ asset('storage/'.$image->image_path) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:border-violet-400"
                            >
                                <img
                                    src="{{ asset('storage/'.$image->image_path) }}"
                                    alt="Foto tambahan {{ $product->name }}"
                                    class="h-full w-full object-cover transition duration-300 hover:scale-105"
                                >
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Informasi Produk --}}
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700">
                        {{ $product->motif_name ?: 'Produk Sasirangan' }}
                    </span>

                    @if ($product->stock > 0)
                        <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                            Tersedia
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-700">
                            Stok Habis
                        </span>
                    @endif
                </div>

                <h1 class="mt-5 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">
                    {{ $product->name }}
                </h1>

                <p class="mt-4 text-3xl font-bold text-violet-700">
                    Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">
                        Stok: {{ number_format($product->stock) }}
                    </span>

                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">
                        Dilihat: {{ number_format($product->view_count) }}
                    </span>

                    @if ($product->material)
                        <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">
                            {{ $product->material }}
                        </span>
                    @endif
                </div>

                {{-- Informasi UMKM --}}
                <div class="mt-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        @if ($product->umkm->logo)
                            <img
                                src="{{ asset('storage/'.$product->umkm->logo) }}"
                                alt="{{ $product->umkm->business_name }}"
                                class="h-14 w-14 rounded-2xl object-cover"
                            >
                        @else
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-xl font-bold text-violet-700">
                                {{ strtoupper(substr($product->umkm->business_name, 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Dijual oleh
                            </p>

                            <p class="mt-1 text-lg font-bold text-slate-900">
                                {{ $product->umkm->business_name }}
                            </p>

                            <p class="text-sm text-slate-500">
                                Pengrajin: {{ $product->umkm->owner_name }}
                            </p>
                        </div>
                    </div>
                </div>

{{-- Tombol Aksi dan Keranjang --}}
<div class="mt-7">
    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="mb-4 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan gagal --}}
    @if (session('error'))
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Form keranjang --}}
    @auth
        @if (auth()->user()->isCustomer())
            @if ($product->stock > 0)
                <form
                    method="POST"
                    action="{{ route('customer.cart.store', $product) }}"
                    class="rounded-2xl border border-violet-200 bg-violet-50 p-5"
                >
                    @csrf

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-violet-950">
                                Tambahkan ke Keranjang
                            </h2>

                            <p class="mt-1 text-sm text-violet-900/70">
                                Pilih jumlah produk yang ingin dibeli.
                            </p>
                        </div>

                        <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-violet-700 shadow-sm">
                            Stok {{ number_format($product->stock) }}
                        </span>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <div class="sm:w-28">
                            <label
                                for="quantity"
                                class="mb-2 block text-xs font-semibold uppercase tracking-wider text-violet-900"
                            >
                                Jumlah
                            </label>

                            <input
                                id="quantity"
                                type="number"
                                name="quantity"
                                value="{{ old('quantity', 1) }}"
                                min="1"
                                max="{{ $product->stock }}"
                                required
                                class="w-full rounded-xl border-violet-200 text-center font-semibold text-slate-900 focus:border-violet-500 focus:ring-violet-500"
                            >
                        </div>

                        <button
                            type="submit"
                            class="mt-auto flex flex-1 items-center justify-center gap-2 rounded-xl bg-violet-700 px-6 py-3 font-semibold text-white transition hover:bg-violet-800"
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

                            Tambah ke Keranjang
                        </button>
                    </div>
                </form>
            @else
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-center">
                    <p class="font-bold text-red-700">
                        Stok produk sedang habis
                    </p>

                    <p class="mt-1 text-sm text-red-600">
                        Produk belum dapat dimasukkan ke keranjang.
                    </p>
                </div>
            @endif
        @else
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                <p class="font-bold text-yellow-900">
                    Gunakan akun Customer
                </p>

                <p class="mt-1 text-sm text-yellow-700">
                    Keranjang belanja hanya tersedia untuk akun Customer.
                </p>
            </div>
        @endif
    @else
        <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5">
            <p class="font-bold text-violet-950">
                Masuk untuk membeli produk
            </p>

            <p class="mt-1 text-sm text-violet-900/70">
                Silakan login atau daftar sebagai Customer untuk menggunakan
                keranjang belanja.
            </p>

            <a
                href="{{ route('login') }}"
                class="mt-4 flex w-full items-center justify-center rounded-xl bg-violet-700 px-6 py-3 font-semibold text-white transition hover:bg-violet-800"
            >
                Masuk Sekarang
            </a>
        </div>
    @endauth

    {{-- Tombol lainnya --}}
    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
        @if ($product->stock > 0 && $product->umkm->whatsapp)
            @php
                $whatsapp = preg_replace(
                    '/[^0-9]/',
                    '',
                    $product->umkm->whatsapp
                );

                if (str_starts_with($whatsapp, '0')) {
                    $whatsapp = '62'.substr($whatsapp, 1);
                }

                $message = urlencode(
                    "Halo, saya tertarik dengan produk {$product->name} di SasiVerse."
                );
            @endphp

            <a
                href="https://wa.me/{{ $whatsapp }}?text={{ $message }}"
                target="_blank"
                rel="noopener noreferrer"
                class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-green-600 px-6 py-4 font-semibold text-white transition hover:bg-green-700"
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
                        d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 11.25c0 4.142-4.03 7.5-9 7.5a10.7 10.7 0 0 1-3.426-.553L3 20.25l1.64-4.1A6.81 6.81 0 0 1 3 11.25c0-4.142 4.03-7.5 9-7.5s9 3.358 9 7.5Z"
                    />
                </svg>

                Hubungi UMKM
            </a>
        @endif

        @auth
            @if (auth()->user()->isCustomer())
                <a
                    href="{{ route('customer.cart.index') }}"
                    class="flex flex-1 items-center justify-center rounded-2xl border border-violet-200 bg-violet-50 px-6 py-4 font-semibold text-violet-700 transition hover:bg-violet-100"
                >
                    Lihat Keranjang
                </a>
            @endif
        @endauth

        <a
            href="{{ route('store.catalog') }}"
            class="flex flex-1 items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Kembali ke Katalog
        </a>
    </div>
</div>


                {{-- Informasi Produk --}}
                <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-bold text-slate-900">
                        Informasi Produk
                    </h2>

                    <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm text-slate-500">
                                Kode Produk
                            </dt>

                            <dd class="mt-1 font-mono font-semibold text-slate-900">
                                {{ $product->upc }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-slate-500">
                                Ukuran
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $product->size ?: '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-slate-500">
                                Bahan
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $product->material ?: '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-slate-500">
                                Motif
                            </dt>

                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ $product->motif_name ?: '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- QR Code --}}
                @if (Route::has('store.product.qr'))
                    <div class="mt-6 overflow-hidden rounded-2xl border border-violet-200 bg-gradient-to-br from-violet-50 to-indigo-50 p-6">
                        <div class="flex flex-col items-center gap-6 sm:flex-row">
                            <div class="h-36 w-36 shrink-0 rounded-2xl border border-violet-100 bg-white p-2 shadow-sm">
                                <img
                                    src="{{ route('store.product.qr', [
                                        'product' => $product->slug,
                                    ]) }}"
                                    alt="QR Code {{ $product->name }}"
                                    class="h-full w-full object-contain"
                                >
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-violet-700">
                                    Identitas Digital
                                </p>

                                <h3 class="mt-2 text-xl font-bold text-violet-950">
                                    Pindai QR Code Produk
                                </h3>

                                <p class="mt-2 text-sm leading-6 text-violet-900/70">
                                    QR Code ini mengarah ke halaman resmi
                                    produk di SasiVerse dan dapat digunakan
                                    untuk memeriksa informasi produk,
                                    pengrajin, motif, serta filosofinya.
                                </p>

                                <div class="mt-4 inline-flex rounded-xl bg-white px-4 py-2 font-mono text-sm font-bold text-violet-900 shadow-sm">
                                    {{ $product->upc }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm lg:col-span-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
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
                            d="M4.5 5.25h15v13.5h-15V5.25Zm3 3h9m-9 3h9m-9 3h6"
                        />
                    </svg>
                </div>

                <h2 class="mt-5 text-xl font-bold text-slate-900">
                    Deskripsi Produk
                </h2>

                <p class="mt-4 whitespace-pre-line leading-8 text-slate-600">
                    {{ $product->description ?: 'Belum ada deskripsi produk.' }}
                </p>
            </div>

            <div class="rounded-3xl border border-violet-100 bg-violet-50 p-7">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
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
                            d="M12 3c3.5 2.3 5.5 5.3 5.5 8.3A5.5 5.5 0 0 1 12 17a5.5 5.5 0 0 1-5.5-5.7C6.5 8.3 8.5 5.3 12 3Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 8v13"
                        />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-bold text-violet-950">
                    Filosofi Motif
                </h2>

                <p class="mt-3 leading-7 text-violet-900/70">
                    {{ $product->motif_philosophy ?: 'Belum ada informasi filosofi motif.' }}
                </p>
            </div>

            <div class="rounded-3xl border border-amber-100 bg-amber-50 p-7">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-6 w-6"
                    >
                        <circle cx="12" cy="12" r="4"></circle>

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 2.5v2M12 19.5v2M2.5 12h2M19.5 12h2M5.3 5.3l1.4 1.4M17.3 17.3l1.4 1.4M18.7 5.3l-1.4 1.4M6.7 17.3l-1.4 1.4"
                        />
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-bold text-amber-950">
                    Filosofi Warna
                </h2>

                <p class="mt-3 leading-7 text-amber-900/70">
                    {{ $product->color_philosophy ?: 'Belum ada informasi filosofi warna.' }}
                </p>
            </div>

            <div class="rounded-3xl bg-slate-900 p-7 text-white shadow-sm">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-violet-200">
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
                            d="m9 12.75 2.25 2.25L15 9.75"
                        />

                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-bold">
                    Identitas Terdaftar
                </h2>

                <p class="mt-3 leading-7 text-slate-300">
                    Produk ini terdaftar di SasiVerse dengan kode
                    <strong class="font-mono text-white">
                        {{ $product->upc }}
                    </strong>.
                </p>

                <p class="mt-3 text-sm leading-6 text-slate-400">
                    Dipasarkan oleh UMKM
                    {{ $product->umkm->business_name }}.
                </p>
            </div>
        </div>

        {{-- Produk Serupa --}}
        @if ($relatedProducts->isNotEmpty())
            <div class="mt-16">
                <div class="flex items-end justify-between gap-5">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                            Rekomendasi
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-slate-900">
                            Produk Serupa
                        </h2>
                    </div>

                    <a
                        href="{{ route('store.catalog') }}"
                        class="hidden text-sm font-semibold text-violet-700 hover:underline sm:block"
                    >
                        Lihat katalog →
                    </a>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        @include(
                            'store.partials.product-card',
                            ['product' => $relatedProduct]
                        )
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection

