@extends('layouts.store')

@section('title', $umkm->business_name.' - SasiVerse')

@section('content')
    @php
        $whatsappNumber = preg_replace(
            '/[^0-9]/',
            '',
            (string) $umkm->whatsapp
        );

        if (str_starts_with($whatsappNumber, '0')) {
            $whatsappNumber =
                '62'.substr($whatsappNumber, 1);
        } elseif (
            $whatsappNumber !== ''
            && str_starts_with($whatsappNumber, '8')
        ) {
            $whatsappNumber = '62'.$whatsappNumber;
        }

        $whatsappMessage = rawurlencode(
            'Halo '.$umkm->business_name
            .', saya melihat profil UMKM Anda di SasiVerse.'
        );
    @endphp

    {{-- Profil UMKM --}}
    <section
        class="relative overflow-hidden border-b border-slate-200 bg-slate-950"
    >
        <div class="absolute inset-0">
            <div
                class="absolute -left-28 top-0 h-80 w-80 rounded-full bg-violet-700/30 blur-3xl"
            ></div>

            <div
                class="absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-fuchsia-600/20 blur-3xl"
            ></div>
        </div>

        <div
            class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16"
        >
            <a
                href="{{ route('store.home') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-violet-200 transition hover:text-white"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="h-4 w-4"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                    />
                </svg>

                Kembali ke beranda
            </a>

            <div
                class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1fr)_340px]"
            >
                <div
                    class="flex flex-col gap-6 sm:flex-row sm:items-start"
                >
                    @if ($umkm->logo)
                        <img
                            src="{{ asset('storage/'.$umkm->logo) }}"
                            alt="Logo {{ $umkm->business_name }}"
                            class="h-28 w-28 shrink-0 rounded-3xl object-cover shadow-2xl ring-1 ring-white/15"
                        >
                    @else
                        <div
                            class="flex h-28 w-28 shrink-0 items-center justify-center rounded-3xl bg-violet-600 text-4xl font-bold text-white shadow-2xl ring-1 ring-white/15"
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

                    <div>
                        <span
                            class="inline-flex rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1.5 text-xs font-semibold text-emerald-300"
                        >
                            UMKM terverifikasi
                        </span>

                        <h1
                            class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl"
                        >
                            {{ $umkm->business_name }}
                        </h1>

                        <p class="mt-3 text-base text-slate-300">
                            Dikelola oleh

                            <span class="font-semibold text-white">
                                {{ $umkm->owner_name }}
                            </span>
                        </p>

                        @if ($umkm->description)
                            <p
                                class="mt-5 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base"
                            >
                                {{ $umkm->description }}
                            </p>
                        @endif
                    </div>
                </div>

                <aside
                    class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm"
                >
                    <h2 class="text-lg font-bold text-white">
                        Informasi UMKM
                    </h2>

                    <div class="mt-5 space-y-5 text-sm">
                        @if ($umkm->address)
                            <div class="flex gap-3">
                                <div
                                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-500/15 text-violet-300"
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
                                            d="M12 21s6-4.35 6-10a6 6 0 1 0-12 0c0 5.65 6 10 6 10Z"
                                        />

                                        <circle
                                            cx="12"
                                            cy="11"
                                            r="2"
                                        />
                                    </svg>
                                </div>

                                <div>
                                    <p class="font-semibold text-white">
                                        Alamat
                                    </p>

                                    <p
                                        class="mt-1 leading-6 text-slate-400"
                                    >
                                        {{ $umkm->address }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($umkm->phone)
                            <div class="flex gap-3">
                                <div
                                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-500/15 text-violet-300"
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
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.143-7.143 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.37-.277.536-.755.417-1.173L6.963 3.102A1.125 1.125 0 0 0 5.872 2.25H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"
                                        />
                                    </svg>
                                </div>

                                <div>
                                    <p class="font-semibold text-white">
                                        Telepon
                                    </p>

                                    <p class="mt-1 text-slate-400">
                                        {{ $umkm->phone }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($whatsappNumber !== '')
                        <a
                            href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                        >
                            Hubungi melalui WhatsApp
                        </a>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    {{-- Katalog produk UMKM --}}
    <section
        class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-16"
    >
        <div
            class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
        >
            <div>
                <p
                    class="text-sm font-semibold uppercase tracking-wider text-violet-700"
                >
                    Katalog UMKM
                </p>

                <h2 class="mt-2 text-3xl font-bold text-slate-900">
                    Produk {{ $umkm->business_name }}
                </h2>

                <p class="mt-2 text-sm text-slate-600">
                    {{ number_format($products->total()) }}
                    produk aktif ditemukan.
                </p>
            </div>

            <form
                action="{{ route('store.umkm.show', $umkm) }}"
                method="GET"
                class="grid w-full gap-3 rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:grid-cols-[minmax(0,1fr)_180px_auto] lg:max-w-3xl"
            >
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari produk, motif, atau bahan..."
                    class="h-11 min-w-0 rounded-xl border border-slate-200 px-4 text-sm text-slate-900 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100"
                >

                <select
                    name="sort"
                    class="h-11 rounded-xl border border-slate-200 px-4 text-sm text-slate-700 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100"
                >
                    <option
                        value="latest"
                        @selected(
                            request('sort', 'latest') === 'latest'
                        )
                    >
                        Terbaru
                    </option>

                    <option
                        value="popular"
                        @selected(request('sort') === 'popular')
                    >
                        Paling populer
                    </option>

                    <option
                        value="price_low"
                        @selected(request('sort') === 'price_low')
                    >
                        Harga terendah
                    </option>

                    <option
                        value="price_high"
                        @selected(request('sort') === 'price_high')
                    >
                        Harga tertinggi
                    </option>

                    <option
                        value="oldest"
                        @selected(request('sort') === 'oldest')
                    >
                        Terlama
                    </option>
                </select>

                <button
                    type="submit"
                    class="h-11 rounded-xl bg-violet-700 px-6 text-sm font-semibold text-white transition hover:bg-violet-800"
                >
                    Terapkan
                </button>
            </form>
        </div>

        @if (
            request()->filled('search')
            || request()->filled('sort')
        )
            <div class="mt-5">
                <a
                    href="{{ route('store.umkm.show', $umkm) }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-violet-700 hover:underline"
                >
                    Hapus filter
                </a>
            </div>
        @endif

        @if ($products->isNotEmpty())
            <div
                class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                @foreach ($products as $product)
                    @include(
                        'store.partials.product-card',
                        ['product' => $product]
                    )
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div
                class="mt-9 rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center"
            >
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 text-violet-700"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.7"
                        stroke="currentColor"
                        class="h-7 w-7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-4.35-4.35m1.1-5.4a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"
                        />
                    </svg>
                </div>

                <h3 class="mt-5 text-lg font-bold text-slate-900">
                    Produk belum ditemukan
                </h3>

                <p
                    class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600"
                >
                    UMKM ini belum memiliki produk aktif atau tidak
                    ada produk yang sesuai dengan pencarian kamu.
                </p>

                @if (
                    request()->filled('search')
                    || request()->filled('sort')
                )
                    <a
                        href="{{ route(
                            'store.umkm.show',
                            $umkm
                        ) }}"
                        class="mt-6 inline-flex rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                    >
                        Tampilkan semua produk
                    </a>
                @endif
            </div>
        @endif
    </section>
@endsection