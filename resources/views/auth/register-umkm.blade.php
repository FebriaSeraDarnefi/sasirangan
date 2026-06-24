<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Daftar UMKM - SasiVerse</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">
    <div class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-xl sm:p-10">
            <div class="mb-8 text-center">
                <a
                    href="{{ url('/') }}"
                    class="text-2xl font-bold text-violet-700"
                >
                    SasiVerse
                </a>

                <h1 class="mt-4 text-3xl font-bold text-slate-900">
                    Daftar sebagai UMKM
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Daftarkan usaha Sasirangan Anda untuk bergabung dengan
                    marketplace SasiVerse.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                    <p class="font-semibold text-red-700">
                        Terdapat data yang belum sesuai:
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('umkm.register.store') }}"
                class="space-y-5"
            >
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="owner_name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nama Pemilik
                        </label>

                        <input
                            id="owner_name"
                            name="owner_name"
                            type="text"
                            value="{{ old('owner_name') }}"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>

                    <div>
                        <label
                            for="business_name"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nama UMKM
                        </label>

                        <input
                            id="business_name"
                            name="business_name"
                            type="text"
                            value="{{ old('business_name') }}"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>

                    <div>
                        <label
                            for="phone"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Nomor Telepon
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone') }}"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>
                </div>

                <div>
                    <label
                        for="whatsapp"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nomor WhatsApp
                    </label>

                    <input
                        id="whatsapp"
                        name="whatsapp"
                        type="text"
                        value="{{ old('whatsapp') }}"
                        placeholder="Contoh: 6281234567890"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>

                <div>
                    <label
                        for="address"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Alamat Usaha
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >{{ old('address') }}</textarea>
                </div>

                <div>
                    <label
                        for="description"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Deskripsi UMKM
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>

                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Konfirmasi Password
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-violet-700 px-5 py-3 font-semibold text-white transition hover:bg-violet-800"
                >
                    Daftar sebagai UMKM
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah memiliki akun?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-violet-700 hover:underline"
                >
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>