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
        content="Masuk ke SasiVerse, platform marketplace dan digitalisasi produk Sasirangan."
    >

    <title>Masuk - SasiVerse</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <main class="grid min-h-screen lg:grid-cols-2">

        {{-- Bagian Informasi dengan Foto Sasirangan --}}
        <section class="relative hidden min-h-screen overflow-hidden bg-slate-950 lg:flex">
            <img
                src="{{ asset('images/sasirangan-login.jpg') }}"
                alt="Pemuda dan pemudi mengenakan busana Sasirangan Kalimantan Selatan"
                class="absolute inset-0 h-full w-full object-cover object-center"
                loading="eager"
            >

            {{-- Lapisan gelap agar teks tetap terbaca --}}
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/70 via-violet-950/45 to-slate-950/95"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-violet-950/70 via-transparent to-slate-950/20"></div>

            {{-- Cahaya dekoratif --}}
            <div class="absolute -left-24 top-1/3 h-80 w-80 rounded-full bg-violet-500/25 blur-3xl"></div>
            <div class="absolute -bottom-32 right-0 h-96 w-96 rounded-full bg-fuchsia-500/15 blur-3xl"></div>

            {{-- Pola Sasirangan sederhana --}}
            <div
                class="absolute inset-0 opacity-[0.06]"
                style="
                    background-image:
                        linear-gradient(45deg, #ffffff 25%, transparent 25%),
                        linear-gradient(-45deg, #ffffff 25%, transparent 25%),
                        linear-gradient(45deg, transparent 75%, #ffffff 75%),
                        linear-gradient(-45deg, transparent 75%, #ffffff 75%);
                    background-size: 56px 56px;
                    background-position:
                        0 0,
                        0 28px,
                        28px -28px,
                        -28px 0;
                "
            ></div>

            <div class="relative z-10 flex w-full flex-col justify-between p-10 xl:p-16">
                {{-- Logo --}}
                <a
                    href="{{ url('/') }}"
                    class="flex w-fit items-center gap-3 text-white"
                >
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/20 bg-white/15 shadow-xl backdrop-blur-md">
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
                                d="M12 8v13M8.5 12.5 12 16l3.5-3.5"
                            />
                        </svg>
                    </div>

                    <div>
                        <p class="text-xl font-bold tracking-wide">
                            SasiVerse
                        </p>

                        <p class="text-xs text-violet-100/80">
                            Warisan Budaya dalam Dunia Digital
                        </p>
                    </div>
                </a>

                {{-- Hero --}}
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-black/20 px-4 py-2 text-sm font-semibold text-white backdrop-blur-md">
                        <span class="h-2 w-2 rounded-full bg-violet-300"></span>
                        Marketplace Sasirangan Kalimantan Selatan
                    </span>

                    <h1 class="mt-6 text-4xl font-bold leading-tight text-white xl:text-5xl">
                        Pesona Sasirangan dalam setiap
                        <span class="text-violet-300">
                            karya dan cerita.
                        </span>
                    </h1>

                    <p class="mt-5 max-w-lg text-base leading-8 text-slate-100/85">
                        Temukan produk asli, kenali filosofi motif, dan dukung
                        pengrajin lokal untuk terus tumbuh di era digital.
                    </p>

                    <div class="mt-8 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/15 bg-black/20 p-4 backdrop-blur-md">
                            <p class="text-2xl font-bold text-white">
                                Lokal
                            </p>
                            <p class="mt-1 text-xs leading-5 text-slate-200/80">
                                Langsung dari pengrajin.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-black/20 p-4 backdrop-blur-md">
                            <p class="text-2xl font-bold text-white">
                                Digital
                            </p>
                            <p class="mt-1 text-xs leading-5 text-slate-200/80">
                                UPC dan QR produk.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-black/20 p-4 backdrop-blur-md">
                            <p class="text-2xl font-bold text-white">
                                Budaya
                            </p>
                            <p class="mt-1 text-xs leading-5 text-slate-200/80">
                                Motif penuh filosofi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 text-xs text-slate-200/70">
                    <p>
                        © {{ date('Y') }} SasiVerse
                    </p>

                    <p class="rounded-full border border-white/15 bg-black/20 px-3 py-1.5 backdrop-blur-md">
                        Sasirangan • Kalimantan Selatan
                    </p>
                </div>
            </div>
        </section>

        {{-- Bagian Login --}}
        <section class="relative flex min-h-screen items-center justify-center px-5 py-10 sm:px-8 lg:px-12">
            {{-- Logo mobile --}}
            <a
                href="{{ url('/') }}"
                class="absolute left-5 top-6 flex items-center gap-3 lg:hidden"
            >
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-700 text-white shadow-md">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
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

                <span class="text-lg font-bold text-slate-900">
                    SasiVerse
                </span>
            </a>

            <div class="w-full max-w-md">
                {{-- Foto Sasirangan pada tampilan mobile --}}
                <div class="relative mb-8 mt-14 overflow-hidden rounded-3xl shadow-xl lg:hidden">
                    <img
                        src="{{ asset('images/sasirangan-login.jpg') }}"
                        alt="Pemuda dan pemudi mengenakan busana Sasirangan"
                        class="h-48 w-full object-cover object-center"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent"></div>

                    <div class="absolute inset-x-0 bottom-0 p-5 text-white">
                        <p class="text-sm font-semibold text-violet-200">
                            Warisan Budaya Kalimantan Selatan
                        </p>

                        <p class="mt-1 text-lg font-bold">
                            Sasirangan tumbuh bersama teknologi.
                        </p>
                    </div>
                </div>
                <div class="mb-8">
                    <p class="text-sm font-semibold text-violet-700">
                        Selamat datang kembali
                    </p>

                    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Masuk ke akun Anda
                    </h2>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Masukkan email dan password untuk mengakses SasiVerse.
                    </p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="mt-0.5 h-5 w-5 shrink-0"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m4.5 12.75 6 6 9-13.5"
                            />
                        </svg>

                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Error login --}}
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="mt-0.5 h-5 w-5 shrink-0"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.947-3.374L10.053 3.38c.865-1.5 3.03-1.5 3.895 0l7.355 12.746ZM12 15.75h.008v.008H12v-.008Z"
                            />
                        </svg>

                        <div>
                            <p class="font-semibold">
                                Login belum berhasil
                            </p>

                            @foreach ($errors->all() as $error)
                                <p class="mt-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login') }}"
                    class="space-y-5"
                >
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Alamat email
                        </label>

                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
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
                                        d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0-8.69 5.793a2.25 2.25 0 0 1-2.12 0L2.25 6.75"
                                    />
                                </svg>
                            </div>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                autofocus
                                required
                                placeholder="nama@email.com"
                                class="block w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-violet-500 focus:ring-4 focus:ring-violet-100"
                            >
                        </div>

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div x-data="{ showPassword: false }">
                        <div class="mb-2 flex items-center justify-between">
                            <label
                                for="password"
                                class="block text-sm font-semibold text-slate-700"
                            >
                                Password
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm font-semibold text-violet-700 transition hover:text-violet-900"
                                >
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
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
                                        d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z"
                                    />
                                </svg>
                            </div>

                            <input
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                placeholder="Masukkan password"
                                class="block w-full rounded-2xl border border-slate-300 bg-white py-3.5 pl-12 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-violet-500 focus:ring-4 focus:ring-violet-100"
                            >

                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-violet-700"
                                aria-label="Tampilkan atau sembunyikan password"
                            >
                                <svg
                                    x-show="!showPassword"
                                    x-cloak
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
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                    />
                                </svg>

                                <svg
                                    x-show="showPassword"
                                    x-cloak
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
                                        d="M3.98 8.223A10.477 10.477 0 0 0 2.036 11.68a1.017 1.017 0 0 0 0 .639C3.423 16.49 7.36 19.5 12 19.5c1.748 0 3.394-.426 4.843-1.18M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638a10.52 10.52 0 0 1-1.249 2.592M6.228 6.228 3 3m3.228 3.228 3.65 3.65m6.244 6.244L21 21m-4.878-4.878-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243"
                                    />
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-violet-700 focus:ring-violet-500"
                        >

                        <label
                            for="remember_me"
                            class="ml-2 text-sm text-slate-600"
                        >
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    {{-- Tombol Login --}}
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-2 rounded-2xl bg-violet-700 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:-translate-y-0.5 hover:bg-violet-800 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-violet-200"
                    >
                        Masuk ke SasiVerse

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="h-4 w-4 transition group-hover:translate-x-1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                            />
                        </svg>
                    </button>
                </form>

                {{-- Pemisah --}}
                <div class="my-7 flex items-center gap-4">
                    <div class="h-px flex-1 bg-slate-200"></div>

                    <span class="text-xs font-medium uppercase tracking-wider text-slate-400">
                        Belum memiliki akun?
                    </span>

                    <div class="h-px flex-1 bg-slate-200"></div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700"
                        >
                            Daftar Customer
                        </a>
                    @endif

                    @if (Route::has('umkm.register'))
                        <a
                            href="{{ route('umkm.register') }}"
                            class="flex items-center justify-center rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-center text-sm font-semibold text-violet-700 transition hover:border-violet-300 hover:bg-violet-100"
                        >
                            Daftar sebagai UMKM
                        </a>
                    @endif
                </div>

                <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                    Dengan masuk, Anda menyetujui ketentuan penggunaan dan
                    kebijakan privasi SasiVerse.
                </p>
            </div>
        </section>
    </main>
</body>
</html>

