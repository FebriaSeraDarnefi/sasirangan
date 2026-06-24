@extends('layouts.store')

@section('title', 'Katalog Produk')

@section('content')
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                Katalog
            </p>

            <h1 class="mt-2 text-4xl font-bold text-slate-900">
                Temukan produk Sasirangan
            </h1>

            <p class="mt-3 max-w-2xl text-slate-500">
                Jelajahi produk dari UMKM dan pengrajin Sasirangan yang telah terdaftar.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <form
            method="GET"
            action="{{ route('store.catalog') }}"
            class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm"
        >
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Cari produk
                    </label>

                    <input
                        name="search"
                        type="search"
                        value="{{ request('search') }}"
                        placeholder="Nama produk, motif, atau UMKM"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Motif
                    </label>

                    <select
                        name="motif"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                        <option value="">Semua motif</option>

                        @foreach ($motifs as $motif)
                            <option
                                value="{{ $motif }}"
                                @selected(request('motif') === $motif)
                            >
                                {{ $motif }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Bahan
                    </label>

                    <select
                        name="material"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                        <option value="">Semua bahan</option>

                        @foreach ($materials as $material)
                            <option
                                value="{{ $material }}"
                                @selected(request('material') === $material)
                            >
                                {{ $material }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Urutkan
                    </label>

                    <select
                        name="sort"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                        <option value="latest">Terbaru</option>
                        <option value="popular" @selected(request('sort') === 'popular')>
                            Terpopuler
                        </option>
                        <option value="price_low" @selected(request('sort') === 'price_low')>
                            Harga terendah
                        </option>
                        <option value="price_high" @selected(request('sort') === 'price_high')>
                            Harga tertinggi
                        </option>
                    </select>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="rounded-xl bg-violet-700 px-6 py-3 text-sm font-semibold text-white hover:bg-violet-800"
                >
                    Terapkan Filter
                </button>

                <a
                    href="{{ route('store.catalog') }}"
                    class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>

        <div class="mt-10 flex items-center justify-between">
            <p class="text-sm text-slate-500">
                Menampilkan
                <strong class="text-slate-900">{{ $products->total() }}</strong>
                produk
            </p>
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($products as $product)
                @include('store.partials.product-card', ['product' => $product])
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <p class="text-xl font-bold text-slate-900">
                        Produk tidak ditemukan
                    </p>

                    <p class="mt-2 text-sm text-slate-500">
                        Coba ubah kata pencarian atau filter yang digunakan.
                    </p>
                </div>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </section>
@endsection