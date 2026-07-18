@extends('layouts.store')

@section('title', 'Marketplace Sasirangan')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div
                class="absolute -left-40 top-0 h-96 w-96 rounded-full bg-violet-700/30 blur-3xl"
            ></div>

            <div
                class="absolute -right-20 bottom-0 h-96 w-96 rounded-full bg-fuchsia-600/20 blur-3xl"
            ></div>
        </div>

        <div
            class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-28"
        >
            <div>
                <span
                    class="inline-flex rounded-full border border-violet-400/30 bg-violet-500/10 px-4 py-2 text-sm font-semibold text-violet-200"
                >
                    Marketplace Sasirangan Kalimantan Selatan
                </span>

                <h1
                    class="mt-7 text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl"
                >
                    Temukan keindahan dalam setiap

                    <span class="text-violet-300">
                        motif Sasirangan.
                    </span>
                </h1>

                <p
                    class="mt-6 max-w-xl text-base leading-8 text-slate-300 sm:text-lg"
                >
                    Belanja langsung dari pengrajin lokal, pelajari
                    filosofi motif dan warna, serta kenali identitas
                    setiap produk.
                </p>

                <form
                    method="GET"
                    action="{{ route('store.catalog') }}"
                    class="mt-9 flex max-w-xl flex-col gap-3 rounded-2xl bg-white p-2 shadow-2xl sm:flex-row"
                >
                    <div class="relative flex-1">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 21-4.35-4.35m1.1-5.4a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"
                            />
                        </svg>

                        <input
                            name="search"
                            type="search"
                            placeholder="Cari produk, motif, atau UMKM..."
                            class="h-12 w-full rounded-xl border-0 py-3 pl-12 pr-4 text-sm text-slate-900 focus:ring-0"
                        >
                    </div>

                    <button
                        type="submit"
                        class="rounded-xl bg-violet-700 px-7 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                    >
                        Cari Produk
                    </button>
                </form>
            </div>

            {{-- Foto utama Sasirangan --}}
            <div>
                <div class="relative mx-auto max-w-xl">
                    <div
                        class="absolute -inset-4 rounded-[2.75rem] bg-gradient-to-br from-violet-500/25 via-fuchsia-500/10 to-transparent blur-2xl"
                    ></div>

                    <div
                        class="relative overflow-hidden rounded-[2.5rem] border border-white/15 bg-white/5 shadow-2xl shadow-violet-950/40"
                    >
                        <div
                            class="aspect-[4/3] sm:aspect-[16/11] lg:aspect-[4/3]"
                        >
                            <img
                                src="{{ asset('images/sasirangan-hero.jpg') }}"
                                alt="Pertunjukan kain Sasirangan Kalimantan Selatan"
                                class="h-full w-full object-cover object-center transition duration-700 hover:scale-105"
                                loading="eager"
                                fetchpriority="high"
                            >
                        </div>

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent"
                        ></div>

                        <div
                            class="absolute inset-0 ring-1 ring-inset ring-white/10"
                        ></div>

                        <div
                            class="absolute right-5 top-5 rounded-full border border-white/20 bg-black/30 px-4 py-2 text-xs font-semibold text-white backdrop-blur-md"
                        >
                            Sasirangan • Kalsel
                        </div>

                        <div
                            class="absolute inset-x-0 bottom-0 p-6 sm:p-8"
                        >
                            <div
                                class="max-w-md rounded-2xl border border-white/15 bg-black/25 p-5 backdrop-blur-md"
                            >
                                <p
                                    class="text-xl font-bold text-white sm:text-2xl"
                                >
                                    Warisan Budaya
                                </p>

                                <p
                                    class="mt-2 text-sm leading-6 text-slate-200"
                                >
                                    Karya tradisional yang berkembang melalui
                                    teknologi digital dan kreativitas generasi
                                    muda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Motif --}}
    @if ($motifs->isNotEmpty())
        <section class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8"
            >
                <div
                    class="flex items-center gap-3 overflow-x-auto pb-1"
                >
                    <span
                        class="shrink-0 text-sm font-semibold text-slate-500"
                    >
                        Jelajahi motif:
                    </span>

                    @foreach ($motifs->take(8) as $motif)
                        <a
                            href="{{ route('store.catalog', [
                                'motif' => $motif,
                            ]) }}"
                            class="shrink-0 rounded-full border border-violet-200 bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700 transition hover:bg-violet-700 hover:text-white"
                        >
                            {{ $motif }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Produk populer --}}
    <section
        class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8"
    >
        <div class="flex items-end justify-between gap-5">
            <div>
                <p
                    class="text-sm font-semibold uppercase tracking-wider text-violet-700"
                >
                    Pilihan populer
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-900">
                    Produk paling banyak dilihat
                </h2>
            </div>

            <a
                href="{{ route('store.catalog', [
                    'sort' => 'popular',
                ]) }}"
                class="hidden text-sm font-semibold text-violet-700 hover:underline sm:block"
            >
                Lihat semua →
            </a>
        </div>

        <div
            class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-4"
        >
            @forelse ($popularProducts as $product)
                @include('store.partials.product-card', [
                    'product' => $product,
                ])
            @empty
                <div
                    class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center text-slate-500"
                >
                    Belum ada produk yang tersedia.
                </div>
            @endforelse
        </div>
    </section>

    {{-- Tentang --}}
    <section class="bg-violet-50">
        <div
            class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-3 lg:px-8"
        >
            <div>
                <p
                    class="text-sm font-semibold uppercase tracking-wider text-violet-700"
                >
                    Mengapa SasiVerse?
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    Lebih dari sekadar marketplace
                </h2>
            </div>

            <div class="rounded-3xl bg-white p-7 shadow-sm">
                <p class="text-lg font-bold text-slate-900">
                    Mendukung Pengrajin Lokal
                </p>

                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Produk dipasarkan langsung oleh UMKM dan pengrajin
                    Sasirangan yang telah terdaftar.
                </p>
            </div>

            <div class="rounded-3xl bg-white p-7 shadow-sm">
                <p class="text-lg font-bold text-slate-900">
                    Identitas Produk Digital
                </p>

                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Informasi motif, filosofi, pengrajin, UPC, dan
                    QR Code tersimpan dalam identitas setiap produk.
                </p>
            </div>
        </div>
    </section>

    {{-- Produk terbaru --}}
    <section
        class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8"
    >
        <div class="flex items-end justify-between gap-5">
            <div>
                <p
                    class="text-sm font-semibold uppercase tracking-wider text-violet-700"
                >
                    Koleksi terbaru
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-900">
                    Produk baru di SasiVerse
                </h2>
            </div>

            <a
                href="{{ route('store.catalog') }}"
                class="hidden text-sm font-semibold text-violet-700 hover:underline sm:block"
            >
                Buka katalog →
            </a>
        </div>

        <div
            class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-4"
        >
            @forelse ($latestProducts as $product)
                @include('store.partials.product-card', [
                    'product' => $product,
                ])
            @empty
                <div
                    class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center text-slate-500"
                >
                    Belum ada produk terbaru.
                </div>
            @endforelse
        </div>
    </section>

    {{-- Semua UMKM --}}
    @if ($umkms->isNotEmpty())
        <section
            class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8"
        >
            <div
                class="overflow-hidden rounded-[2rem] bg-slate-900 px-6 py-10 sm:px-10"
            >
                <div
                    class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between"
                >
                    <div>
                        <p
                            class="text-sm font-semibold uppercase tracking-wider text-violet-300"
                        >
                            Pengrajin lokal
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-white">
                            UMKM yang bergabung
                        </h2>

                        <p
                            class="mt-3 max-w-2xl text-sm leading-6 text-slate-400"
                        >
                            Jelajahi profil pengrajin dan lihat seluruh
                            katalog produk Sasirangan dari setiap UMKM.
                        </p>
                    </div>

                    <div
                        class="inline-flex w-fit items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-violet-200"
                    >
                        {{ number_format($umkms->count()) }} UMKM aktif
                    </div>
                </div>

                <div
                    class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    @foreach ($umkms as $umkm)
                        <a
                            href="{{ route(
                                'store.umkm.show',
                                $umkm
                            ) }}"
                            class="group flex min-h-64 flex-col rounded-2xl border border-white/10 bg-white/5 p-5 transition duration-300 hover:-translate-y-1 hover:border-violet-400/50 hover:bg-white/10 hover:shadow-xl hover:shadow-violet-950/30"
                        >
                            <div
                                class="flex items-start justify-between gap-4"
                            >
                                @if ($umkm->logo)
                                    <img
                                        src="{{ asset(
                                            'storage/'.$umkm->logo
                                        ) }}"
                                        alt="Logo {{ $umkm->business_name }}"
                                        class="h-14 w-14 rounded-2xl object-cover ring-1 ring-white/10"
                                        loading="lazy"
                                    >
                                @else
                                    <div
                                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-500/20 text-lg font-bold text-violet-200"
                                    >
                                        {{ strtoupper(
                                            substr(
                                                $umkm->business_name,
                                                0,
                                                1
                                            )
                                        ) }}
                                    </div>
                                @endif

                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-violet-300 transition group-hover:bg-violet-600 group-hover:text-white"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="h-5 w-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                                        />
                                    </svg>
                                </span>
                            </div>

                            <h3
                                class="mt-5 text-lg font-bold text-white transition group-hover:text-violet-200"
                            >
                                {{ $umkm->business_name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ $umkm->owner_name }}
                            </p>

                            @if ($umkm->description)
                                <p
                                    class="mt-4 text-sm leading-6 text-slate-400"
                                >
                                    {{ \Illuminate\Support\Str::limit(
                                        $umkm->description,
                                        90
                                    ) }}
                                </p>
                            @elseif ($umkm->address)
                                <p
                                    class="mt-4 text-sm leading-6 text-slate-400"
                                >
                                    {{ \Illuminate\Support\Str::limit(
                                        $umkm->address,
                                        90
                                    ) }}
                                </p>
                            @endif

                            <div
                                class="mt-auto flex items-center justify-between gap-3 border-t border-white/10 pt-5"
                            >
                                <span
                                    class="text-sm font-semibold text-violet-300"
                                >
                                    {{ number_format(
                                        $umkm->products_count
                                    ) }}
                                    produk
                                </span>

                                <span
                                    class="text-xs font-semibold text-slate-400 transition group-hover:text-white"
                                >
                                    Profil & katalog
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section
            class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8"
        >
            <div
                class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center text-slate-500"
            >
                Belum ada UMKM aktif yang bergabung.
            </div>
        </section>
    @endif
@endsection