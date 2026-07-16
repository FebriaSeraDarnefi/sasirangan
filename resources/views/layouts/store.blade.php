<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="SasiVerse, marketplace dan media digitalisasi produk Sasirangan."
    >

    <title>@yield('title', 'Toko Sasirangan') - SasiVerse</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    @php
        $cartCount = 0;

        if (
            auth()->check()
            && auth()->user()->isCustomer()
        ) {
            $customerCart = auth()->user()->cart;

            $cartCount = $customerCart
                ? $customerCart->items()->sum('quantity')
                : 0;
        }
    @endphp

    {{-- Header --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
            {{-- Logo --}}
            <a
                href="{{ route('store.home') }}"
                class="flex shrink-0 items-center gap-3"
            >
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-700 text-white shadow-lg shadow-violet-200">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        class="h-7 w-7"
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

                <div>
                    <p class="text-lg font-bold text-slate-900">
                        SasiVerse
                    </p>

                    <p class="hidden text-xs text-slate-500 sm:block">
                        Pesona Sasirangan Digital
                    </p>
                </div>
            </a>

            {{-- Navigasi desktop --}}
            <nav class="hidden items-center gap-8 md:flex">
                <a
                    href="{{ route('store.home') }}"
                    class="text-sm font-semibold transition
                        {{ request()->routeIs('store.home')
                            ? 'text-violet-700'
                            : 'text-slate-600 hover:text-violet-700' }}"
                >
                    Beranda
                </a>

                <a
                    href="{{ route('store.catalog') }}"
                    class="text-sm font-semibold transition
                        {{ request()->routeIs(
                            'store.catalog',
                            'store.product.show'
                        )
                            ? 'text-violet-700'
                            : 'text-slate-600 hover:text-violet-700' }}"
                >
                    Katalog
                </a>

                <a
                    href="{{ route('store.education') }}"
                    class="text-sm font-semibold transition
                        {{ request()->routeIs('store.education')
                            ? 'text-violet-700'
                            : 'text-slate-600 hover:text-violet-700' }}"
                >
                    Edukasi
                </a>

                @auth
                    @if (
                        auth()->user()->isCustomer()
                        && Route::has('customer.cart.index')
                    )
                        <a
                            href="{{ route('customer.cart.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold transition
                                {{ request()->routeIs('customer.cart.*')
                                    ? 'text-violet-700'
                                    : 'text-slate-600 hover:text-violet-700' }}"
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

                                <circle
                                    cx="10"
                                    cy="20"
                                    r="1"
                                />

                                <circle
                                    cx="18"
                                    cy="20"
                                    r="1"
                                />
                            </svg>

                            Keranjang

                            <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-violet-700 px-2 text-xs font-bold text-white">
                                {{ number_format($cartCount) }}
                            </span>
                        </a>
                    @endif
                @endauth
            </nav>

            {{-- Aksi pengguna --}}
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    {{-- Tombol keranjang versi mobile --}}
                    @if (
                        auth()->user()->isCustomer()
                        && Route::has('customer.cart.index')
                    )
                        <a
                            href="{{ route('customer.cart.index') }}"
                            class="relative inline-flex h-11 w-11 items-center justify-center rounded-xl border border-violet-200 bg-violet-50 text-violet-700 transition hover:bg-violet-100 md:hidden"
                            aria-label="Keranjang"
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

                                <circle
                                    cx="10"
                                    cy="20"
                                    r="1"
                                />

                                <circle
                                    cx="18"
                                    cy="20"
                                    r="1"
                                />
                            </svg>

                            @if ($cartCount > 0)
                                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>
                    @endif

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-800"
                    >
                        Dashboard
                    </a>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="hidden sm:block"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                        >
                            Keluar
                        </button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="hidden text-sm font-semibold text-slate-600 transition hover:text-violet-700 sm:block"
                    >
                        Masuk
                    </a>

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="rounded-xl bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-800"
                        >
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Navigasi mobile --}}
        <div class="border-t border-slate-100 bg-white md:hidden">
            <nav class="mx-auto flex max-w-7xl items-center justify-center gap-8 overflow-x-auto px-4 py-3">
                <a
                    href="{{ route('store.home') }}"
                    class="whitespace-nowrap text-sm font-semibold
                        {{ request()->routeIs('store.home')
                            ? 'text-violet-700'
                            : 'text-slate-600' }}"
                >
                    Beranda
                </a>

                <a
                    href="{{ route('store.catalog') }}"
                    class="whitespace-nowrap text-sm font-semibold
                        {{ request()->routeIs(
                            'store.catalog',
                            'store.product.show'
                        )
                            ? 'text-violet-700'
                            : 'text-slate-600' }}"
                >
                    Katalog
                </a>


                <a
                    href="{{ route('store.education') }}"
                    class="whitespace-nowrap text-sm font-semibold
                        {{ request()->routeIs('store.education')
                            ? 'text-violet-700'
                            : 'text-slate-600' }}"
                >
                    Edukasi
                </a>

                @auth
                    @if (
                        auth()->user()->isCustomer()
                        && Route::has('customer.cart.index')
                    )
                        <a
                            href="{{ route('customer.cart.index') }}"
                            class="inline-flex items-center gap-2 whitespace-nowrap text-sm font-semibold
                                {{ request()->routeIs('customer.cart.*')
                                    ? 'text-violet-700'
                                    : 'text-slate-600' }}"
                        >
                            Keranjang

                            <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-violet-700 px-1.5 text-[10px] font-bold text-white">
                                {{ number_format($cartCount) }}
                            </span>
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    {{-- Konten halaman --}}
    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20 bg-slate-950 text-slate-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-3 lg:px-8">
            <div>
                <p class="text-xl font-bold text-white">
                    SasiVerse
                </p>

                <p class="mt-4 max-w-sm text-sm leading-7 text-slate-400">
                    Marketplace dan media digitalisasi Sasirangan untuk
                    mendukung pengrajin lokal serta memperkenalkan budaya
                    Kalimantan Selatan.
                </p>
            </div>

            <div>
                <p class="font-semibold text-white">
                    Jelajahi
                </p>

                <div class="mt-4 space-y-3 text-sm">
                    <a
                        href="{{ route('store.home') }}"
                        class="block transition hover:text-white"
                    >
                        Beranda
                    </a>

                    <a
                        href="{{ route('store.catalog') }}"
                        class="block transition hover:text-white"
                    >
                        Katalog Produk
                    </a>


                    <a
                        href="{{ route('store.education') }}"
                        class="block transition hover:text-white"
                    >
                        Edukasi Sasirangan
                    </a>

                    @auth
                        @if (
                            auth()->user()->isCustomer()
                            && Route::has('customer.cart.index')
                        )
                            <a
                                href="{{ route('customer.cart.index') }}"
                                class="block transition hover:text-white"
                            >
                                Keranjang Belanja
                            </a>
                        @endif
                    @endauth

                    @guest
                        @if (Route::has('umkm.register'))
                            <a
                                href="{{ route('umkm.register') }}"
                                class="block transition hover:text-white"
                            >
                                Daftar sebagai UMKM
                            </a>
                        @endif
                    @endguest
                </div>
            </div>

            <div>
                <p class="font-semibold text-white">
                    Tentang SasiVerse
                </p>

                <p class="mt-4 text-sm leading-7 text-slate-400">
                    Setiap produk dapat memiliki identitas UPC dan QR Code
                    untuk menampilkan asal, motif, filosofi, dan pengrajinnya.
                </p>
            </div>
        </div>

        <div class="border-t border-slate-800">
            <div class="mx-auto max-w-7xl px-4 py-6 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
                © {{ date('Y') }} SasiVerse. Warisan budaya dalam dunia digital.
            </div>
        </div>
    </footer>
</body>
</html>