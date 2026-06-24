
@extends('layouts.store')

@section('title', 'Keranjang Belanja')

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
                Keranjang
            </span>
        </div>

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                    Belanja
                </p>

                <h1 class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">
                    Keranjang Belanja
                </h1>

                <p class="mt-2 text-slate-500">
                    Terdapat
                    <span class="font-semibold text-slate-700">
                        {{ number_format($totalQuantity ?? 0) }}
                    </span>
                    item di dalam keranjang.
                </p>
            </div>

            @if ($cart->items->isNotEmpty())
                <form
                    method="POST"
                    action="{{ route('customer.cart.clear') }}"
                    onsubmit="return confirm('Yakin ingin mengosongkan seluruh keranjang?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100"
                    >
                        Kosongkan Keranjang
                    </button>
                </form>
            @endif
        </div>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if (session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Error validasi --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <p class="font-semibold">
                    Terdapat kesalahan:
                </p>

                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Keranjang kosong --}}
        @if ($cart->items->isEmpty())
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
                            d="M3 3h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H6"
                        />

                        <circle cx="10" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                    </svg>
                </div>

                <h2 class="mt-6 text-2xl font-bold text-slate-900">
                    Keranjang masih kosong
                </h2>

                <p class="mx-auto mt-3 max-w-lg text-sm leading-7 text-slate-500">
                    Jelajahi produk Sasirangan dari berbagai UMKM dan
                    tambahkan produk yang disukai ke dalam keranjang.
                </p>

                <a
                    href="{{ route('store.catalog') }}"
                    class="mt-7 inline-flex items-center justify-center rounded-xl bg-violet-700 px-6 py-3 font-semibold text-white transition hover:bg-violet-800"
                >
                    Lihat Katalog
                </a>
            </div>
        @else
            <div class="mt-10 grid gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
                {{-- Daftar produk --}}
                <div class="space-y-7">
                    @foreach ($groupedItems as $items)
                        @php
                            $firstItem = $items->first();
                            $umkm = $firstItem?->product?->umkm;

                            $umkmSubtotal = $items->sum(function ($item) {
                                return (float) $item->price
                                    * $item->quantity;
                            });
                        @endphp

                        @if ($umkm)
                            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                                {{-- Header UMKM --}}
                                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                        <div class="flex items-center gap-4">
                                            @if ($umkm->logo)
                                                <img
                                                    src="{{ asset('storage/'.$umkm->logo) }}"
                                                    alt="{{ $umkm->business_name }}"
                                                    class="h-12 w-12 rounded-xl border border-slate-200 object-cover"
                                                >
                                            @else
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-lg font-bold text-violet-700">
                                                    {{ strtoupper(substr($umkm->business_name, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-wider text-violet-700">
                                                    UMKM
                                                </p>

                                                <h2 class="mt-1 text-lg font-bold text-slate-900">
                                                    {{ $umkm->business_name }}
                                                </h2>

                                                <p class="text-sm text-slate-500">
                                                    Pengrajin: {{ $umkm->owner_name }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-left sm:text-right">
                                            <p class="text-xs text-slate-500">
                                                Subtotal UMKM
                                            </p>

                                            <p class="mt-1 font-bold text-violet-700">
                                                Rp{{ number_format(
                                                    $umkmSubtotal,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Produk dari UMKM --}}
                                <div class="divide-y divide-slate-100">
                                    @foreach ($items as $item)
                                        @php
                                            $product = $item->product;

                                            $subtotal = (
                                                (float) $item->price
                                                * $item->quantity
                                            );

                                            $isAvailable = (
                                                $product->status === 'active'
                                                && $product->stock > 0
                                                && $product->umkm
                                                && $product->umkm->verification_status === 'active'
                                            );
                                        @endphp

                                        <div class="p-6">
                                            <div class="flex flex-col gap-5 sm:flex-row">
                                                {{-- Foto produk --}}
                                                <a
                                                    href="{{ route('store.product.show', [
                                                        'product' => $product->slug,
                                                    ]) }}"
                                                    class="h-28 w-28 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100"
                                                >
                                                    @if ($product->main_image)
                                                        <img
                                                            src="{{ asset('storage/'.$product->main_image) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-full w-full object-cover transition duration-300 hover:scale-105"
                                                        >
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-2xl font-bold text-violet-700">
                                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </a>

                                                <div class="min-w-0 flex-1">
                                                    <div class="flex flex-col justify-between gap-4 sm:flex-row">
                                                        {{-- Informasi produk --}}
                                                        <div>
                                                            <a
                                                                href="{{ route('store.product.show', [
                                                                    'product' => $product->slug,
                                                                ]) }}"
                                                                class="text-lg font-bold text-slate-900 transition hover:text-violet-700"
                                                            >
                                                                {{ $product->name }}
                                                            </a>

                                                            <p class="mt-1 text-sm text-slate-500">
                                                                {{ $product->motif_name ?: 'Produk Sasirangan' }}
                                                            </p>

                                                            <p class="mt-1 font-mono text-xs text-slate-400">
                                                                {{ $product->upc }}
                                                            </p>

                                                            <p class="mt-3 font-semibold text-violet-700">
                                                                Rp{{ number_format(
                                                                    (float) $item->price,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </p>

                                                            <p class="mt-1 text-xs text-slate-500">
                                                                Stok tersedia:
                                                                {{ number_format($product->stock) }}
                                                            </p>
                                                        </div>

                                                        {{-- Subtotal --}}
                                                        <div class="text-left sm:text-right">
                                                            <p class="text-sm text-slate-500">
                                                                Subtotal
                                                            </p>

                                                            <p class="mt-1 text-lg font-bold text-slate-900">
                                                                Rp{{ number_format(
                                                                    $subtotal,
                                                                    0,
                                                                    ',',
                                                                    '.'
                                                                ) }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- Status tidak tersedia --}}
                                                    @unless ($isAvailable)
                                                        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                                                            Produk ini sedang tidak tersedia dan tidak dapat dilanjutkan ke checkout.
                                                        </div>
                                                    @endunless

                                                    {{-- Update jumlah dan hapus --}}
                                                    <div class="mt-5 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'customer.cart.update',
                                                                $item
                                                            ) }}"
                                                            class="flex flex-wrap items-end gap-3"
                                                        >
                                                            @csrf
                                                            @method('PATCH')

                                                            <div>
                                                                <label
                                                                    for="quantity-{{ $item->id }}"
                                                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500"
                                                                >
                                                                    Jumlah
                                                                </label>

                                                                <input
                                                                    id="quantity-{{ $item->id }}"
                                                                    type="number"
                                                                    name="quantity"
                                                                    value="{{ $item->quantity }}"
                                                                    min="1"
                                                                    max="{{ max(1, $product->stock) }}"
                                                                    {{ ! $isAvailable ? 'disabled' : '' }}
                                                                    class="w-24 rounded-xl border-slate-300 text-center font-semibold focus:border-violet-500 focus:ring-violet-500 disabled:cursor-not-allowed disabled:bg-slate-100"
                                                                >
                                                            </div>

                                                            <button
                                                                type="submit"
                                                                {{ ! $isAvailable ? 'disabled' : '' }}
                                                                class="rounded-xl bg-violet-100 px-4 py-2.5 text-sm font-semibold text-violet-700 transition hover:bg-violet-200 disabled:cursor-not-allowed disabled:opacity-50"
                                                            >
                                                                Perbarui
                                                            </button>
                                                        </form>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'customer.cart.destroy',
                                                                $item
                                                            ) }}"
                                                            onsubmit="return confirm('Hapus produk ini dari keranjang?')"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="submit"
                                                                class="text-sm font-semibold text-red-600 transition hover:text-red-700 hover:underline"
                                                            >
                                                                Hapus Produk
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Ringkasan belanja --}}
                <aside>
                    <div class="sticky top-24 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Ringkasan Belanja
                        </h2>

                        <div class="mt-6 space-y-4 border-b border-slate-200 pb-6">
                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">
                                    Total item
                                </span>

                                <span class="font-semibold text-slate-900">
                                    {{ number_format($totalQuantity ?? 0) }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">
                                    Jumlah UMKM
                                </span>

                                <span class="font-semibold text-slate-900">
                                    {{ number_format($groupedItems->count()) }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4 text-sm">
                                <span class="text-slate-500">
                                    Ongkos kirim
                                </span>

                                <span class="font-semibold text-slate-500">
                                    Dihitung saat checkout
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 flex items-end justify-between gap-4">
                            <span class="font-semibold text-slate-600">
                                Total Belanja
                            </span>

                            <span class="text-right text-2xl font-bold text-violet-700">
                                Rp{{ number_format(
                                    (float) ($total ?? 0),
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </span>
                        </div>

                            <a
                                href="{{ route('customer.checkout.create') }}"
                                class="mt-6 flex w-full items-center justify-center rounded-xl bg-violet-700 px-5 py-4 font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-800"
                            >
                                Lanjut Checkout
                            </a>



                        <a
                            href="{{ route('store.catalog') }}"
                            class="mt-4 flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Lanjut Belanja
                        </a>
                    </div>
                </aside>
            </div>
        @endif
    </section>
@endsection

