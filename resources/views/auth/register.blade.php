
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar Customer - SasiVerse</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <div class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-xl sm:p-10">
            {{-- Header --}}
            <div class="mb-8 text-center">
                <a
                    href="{{ route('store.home') }}"
                    class="text-2xl font-bold text-violet-700"
                >
                    SasiVerse
                </a>

                <div class="mx-auto mt-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-8 w-8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                        />
                    </svg>
                </div>

                <h1 class="mt-5 text-3xl font-bold text-slate-900">
                    Daftar sebagai Customer
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Buat akun untuk membeli produk Sasirangan dari berbagai
                    UMKM di SasiVerse.
                </p>
            </div>

            {{-- Error validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                    <p class="font-semibold text-red-700">
                        Terdapat data yang belum sesuai:
                    </p>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('register') }}"
                class="space-y-5"
            >
                @csrf

                {{-- Role customer --}}
                <input
                    type="hidden"
                    name="role"
                    value="customer"
                >

                {{-- Nama dan nomor HP --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nama Lengkap
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                        >

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="phone"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nomor HP
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            value="{{ old('phone') }}"
                            required
                            autocomplete="tel"
                            inputmode="numeric"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                        >

                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Email
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="contoh@email.com"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Password
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                        >

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Konfirmasi Password
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>
                </div>

                {{-- Informasi customer --}}
                <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
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
                                    d="M11.25 11.25 12 10.5m0 0 .75.75M12 10.5v5.25m9-3.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="font-semibold text-violet-900">
                                Keuntungan memiliki akun Customer
                            </p>

                            <p class="mt-1 text-sm leading-6 text-violet-700">
                                Kamu dapat menyimpan keranjang, melakukan
                                checkout, mengunggah pembayaran, melacak
                                pengiriman, dan melihat riwayat pesanan.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tombol daftar --}}
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-violet-700 px-5 py-3.5 font-semibold text-white transition hover:bg-violet-800 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"
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
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.125v-.375A4.125 4.125 0 0 1 7.125 14.625h4.5a4.125 4.125 0 0 1 4.125 4.125v.375"
                        />
                    </svg>

                    Daftar sebagai Customer
                </button>
            </form>

            {{-- Login --}}
            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah memiliki akun?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-violet-700 hover:underline"
                >
                    Masuk di sini
                </a>
            </p>

            {{-- Daftar UMKM --}}
            @if (Route::has('umkm.register'))
                <div class="mt-6 border-t border-slate-200 pt-6 text-center">
                    <p class="text-sm text-slate-500">
                        Memiliki usaha Sasirangan?
                    </p>

                    <a
                        href="{{ route('umkm.register') }}"
                        class="mt-2 inline-flex items-center gap-2 font-semibold text-violet-700 hover:underline"
                    >
                        Daftar sebagai UMKM

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-4 w-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                            />
                        </svg>
                    </a>
                </div>
            @endif

            {{-- Kembali --}}
            <div class="mt-6 text-center">
                <a
                    href="{{ route('store.home') }}"
                    class="text-sm font-semibold text-slate-500 transition hover:text-violet-700"
                >
                    ← Kembali ke halaman utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>
