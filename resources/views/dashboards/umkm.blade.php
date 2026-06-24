@extends('layouts.dashboard')

@section('title', 'Dashboard UMKM')

@section('content')
    @if (! $umkm)
        <div class="rounded-3xl border border-yellow-200 bg-yellow-50 p-8">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-700">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-7 w-7"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m0 3.75h.008v.008H12v-.008Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.34 3.94 2.66 17.25A2.25 2.25 0 0 0 4.61 20.63h14.78a2.25 2.25 0 0 0 1.95-3.38L13.66 3.94a1.92 1.92 0 0 0-3.32 0Z"
                    />
                </svg>
            </div>

            <h1 class="mt-5 text-2xl font-bold text-yellow-900">
                Profil UMKM belum tersedia
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-7 text-yellow-800">
                Akun Anda belum memiliki profil UMKM. Silakan lengkapi
                pendaftaran UMKM agar dapat mengelola produk dan menerima
                pesanan dari Customer.
            </p>
        </div>
    @else
        @php
            $statusClass = match ($umkm->verification_status) {
                'active' => 'bg-green-100 text-green-700',
                'pending' => 'bg-yellow-100 text-yellow-700',
                'rejected' => 'bg-red-100 text-red-700',
                'inactive' => 'bg-slate-200 text-slate-700',
                default => 'bg-slate-100 text-slate-700',
            };

            $statusLabel = match ($umkm->verification_status) {
                'active' => 'Aktif',
                'pending' => 'Menunggu Verifikasi',
                'rejected' => 'Ditolak',
                'inactive' => 'Nonaktif',
                default => ucfirst($umkm->verification_status),
            };

            $orderStatuses = [
                'pending' => [
                    'label' => 'Menunggu Pembayaran',
                    'class' => 'bg-yellow-100 text-yellow-700',
                ],
                'processing' => [
                    'label' => 'Sedang Diproses',
                    'class' => 'bg-blue-100 text-blue-700',
                ],
                'packed' => [
                    'label' => 'Sudah Dikemas',
                    'class' => 'bg-indigo-100 text-indigo-700',
                ],
                'shipped' => [
                    'label' => 'Sedang Dikirim',
                    'class' => 'bg-violet-100 text-violet-700',
                ],
                'completed' => [
                    'label' => 'Selesai',
                    'class' => 'bg-green-100 text-green-700',
                ],
                'cancelled' => [
                    'label' => 'Dibatalkan',
                    'class' => 'bg-red-100 text-red-700',
                ],
            ];
        @endphp

        {{-- Pesan berhasil --}}
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Hero dashboard --}}
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-800 via-violet-900 to-indigo-950 p-7 text-white shadow-xl sm:p-9">
            <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/10"></div>

            <div class="absolute -bottom-24 right-32 h-56 w-56 rounded-full bg-violet-300/10"></div>

            <div class="relative flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <p class="text-sm font-semibold text-violet-200">
                            Dashboard UMKM
                        </p>

                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <h1 class="mt-3 text-3xl font-bold sm:text-4xl">
                        {{ $umkm->business_name }}
                    </h1>

                    <p class="mt-3 text-sm text-violet-100/80">
                        Dikelola oleh {{ $umkm->owner_name }}
                    </p>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-violet-100/75">
                        Kelola produk Sasirangan, pantau stok, dan lihat
                        perkembangan pesanan usaha Anda melalui satu dashboard.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    {{-- Tombol pesanan masuk --}}
                    @if (
                        $umkm->verification_status === 'active'
                        && Route::has('umkm.orders.index')
                    )
                        <a
                            href="{{ route('umkm.orders.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-600"
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
                                    d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.064v6.75a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25v-6.75c0-.936.616-1.78 1.5-2.064m16.5 0A2.25 2.25 0 0 0 18.75 6.45H5.25A2.25 2.25 0 0 0 3.75 8.511m16.5 0-7.5 4.875a1.5 1.5 0 0 1-1.5 0L3.75 8.511"
                                />
                            </svg>

                            Pesanan Masuk
                        </a>
                    @endif

                    <a
                        href="{{ route('umkm.products.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-violet-800 transition hover:bg-violet-50"
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
                                d="M3.75 6.75h16.5v13.5H3.75V6.75Zm3-3h10.5v3H6.75v-3Z"
                            />
                        </svg>

                        Kelola Produk
                    </a>

                    <a
                        href="{{ route('umkm.profile.edit') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20"
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
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                            />
                        </svg>

                        Profil UMKM
                    </a>
                </div>
            </div>
        </section>

        {{-- Pemberitahuan UMKM belum aktif --}}
        @if ($umkm->verification_status !== 'active')
            <div class="mt-6 rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-yellow-100 text-yellow-700">
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
                                d="M12 6v6l4 2"
                            />

                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </div>

                    <div>
                        <p class="font-bold text-yellow-900">
                            UMKM belum aktif
                        </p>

                        <p class="mt-1 text-sm leading-6 text-yellow-800">
                            Produk baru hanya dapat ditambahkan dan pesanan
                            hanya dapat diproses setelah pendaftaran UMKM
                            disetujui oleh Admin.
                        </p>

                        @if (
                            $umkm->verification_status === 'rejected'
                            && $umkm->rejection_reason
                        )
                            <div class="mt-3 rounded-xl bg-red-100 p-3 text-sm text-red-700">
                                <strong>Alasan penolakan:</strong>

                                {{ $umkm->rejection_reason }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Statistik --}}
        <section class="mt-8">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-900">
                    Ringkasan Usaha
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Statistik terbaru dari aktivitas UMKM Anda.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
                {{-- Jumlah produk --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
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
                                d="M3.75 6.75h16.5v13.5H3.75V6.75Zm3-3h10.5v3H6.75v-3Z"
                            />
                        </svg>
                    </div>

                    <p class="mt-5 text-sm text-slate-500">
                        Jumlah Produk
                    </p>

                    <p class="mt-1 text-3xl font-bold text-slate-900">
                        {{ number_format($statistics['products'] ?? 0) }}
                    </p>
                </div>

                {{-- Produk aktif --}}
                <div class="rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
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
                                d="m4.5 12.75 6 6 9-13.5"
                            />
                        </svg>
                    </div>

                    <p class="mt-5 text-sm text-green-700">
                        Produk Aktif
                    </p>

                    <p class="mt-1 text-3xl font-bold text-green-800">
                        {{ number_format($statistics['active_products'] ?? 0) }}
                    </p>
                </div>

                {{-- Stok habis --}}
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-100 text-red-700">
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
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>
                    </div>

                    <p class="mt-5 text-sm text-red-700">
                        Stok Habis
                    </p>

                    <p class="mt-1 text-3xl font-bold text-red-800">
                        {{ number_format($statistics['out_of_stock'] ?? 0) }}
                    </p>
                </div>

                {{-- Jumlah pesanan --}}
                @if (
                    $umkm->verification_status === 'active'
                    && Route::has('umkm.orders.index')
                )
                    <a
                        href="{{ route('umkm.orders.index') }}"
                        class="block rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
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
                                    d="M3.75 5.25h16.5v13.5H3.75V5.25Zm3 3h10.5m-10.5 3h7.5"
                                />
                            </svg>
                        </div>

                        <p class="mt-5 text-sm text-blue-700">
                            Jumlah Pesanan
                        </p>

                        <p class="mt-1 text-3xl font-bold text-blue-800">
                            {{ number_format($statistics['orders'] ?? 0) }}
                        </p>

                        <p class="mt-3 text-xs font-semibold text-blue-700">
                            Lihat pesanan →
                        </p>
                    </a>
                @else
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
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
                                    d="M3.75 5.25h16.5v13.5H3.75V5.25Zm3 3h10.5m-10.5 3h7.5"
                                />
                            </svg>
                        </div>

                        <p class="mt-5 text-sm text-blue-700">
                            Jumlah Pesanan
                        </p>

                        <p class="mt-1 text-3xl font-bold text-blue-800">
                            {{ number_format($statistics['orders'] ?? 0) }}
                        </p>
                    </div>
                @endif

                {{-- Pendapatan --}}
                <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5 shadow-sm">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 font-bold text-violet-700">
                        Rp
                    </div>

                    <p class="mt-5 text-sm text-violet-700">
                        Pendapatan Dibayar
                    </p>

                    <p class="mt-1 text-xl font-bold text-violet-800">
                        Rp{{ number_format(
                            (float) ($statistics['paid_revenue'] ?? 0),
                            0,
                            ',',
                            '.'
                        ) }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Akses cepat --}}
        <section class="mt-8">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-slate-900">
                    Akses Cepat
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola kegiatan utama UMKM Anda.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                {{-- Pesanan masuk --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
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
                                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.064v6.75a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25v-6.75c0-.936.616-1.78 1.5-2.064m16.5 0A2.25 2.25 0 0 0 18.75 6.45H5.25A2.25 2.25 0 0 0 3.75 8.511m16.5 0-7.5 4.875a1.5 1.5 0 0 1-1.5 0L3.75 8.511"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Pesanan Masuk
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Lihat pesanan customer yang sudah dibayar dan berisi
                        produk milik UMKM Anda.
                    </p>

                    @if (
                        $umkm->verification_status === 'active'
                        && Route::has('umkm.orders.index')
                    )
                        <a
                            href="{{ route('umkm.orders.index') }}"
                            class="mt-5 inline-flex text-sm font-semibold text-violet-700 hover:underline"
                        >
                            Lihat semua pesanan →
                        </a>
                    @else
                        <p class="mt-5 text-sm font-semibold text-yellow-700">
                            UMKM belum aktif
                        </p>
                    @endif
                </div>

                {{-- Tambah produk --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
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
                                d="M12 6v12m6-6H6"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Tambah Produk
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Tambahkan produk baru beserta foto, harga, stok,
                        motif, dan informasi filosofinya.
                    </p>

                    @if ($umkm->verification_status === 'active')
                        <a
                            href="{{ route('umkm.products.create') }}"
                            class="mt-5 inline-flex text-sm font-semibold text-violet-700 hover:underline"
                        >
                            Tambah produk →
                        </a>
                    @else
                        <p class="mt-5 text-sm font-semibold text-yellow-700">
                            Menunggu persetujuan Admin
                        </p>
                    @endif
                </div>

                {{-- Kelola produk --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
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
                                d="M4.5 5.25h15v13.5h-15V5.25Zm3 3h9m-9 3h6"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Kelola Produk
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Ubah informasi produk, harga, stok, status, dan foto
                        produk yang sudah ditambahkan.
                    </p>

                    <a
                        href="{{ route('umkm.products.index') }}"
                        class="mt-5 inline-flex text-sm font-semibold text-violet-700 hover:underline"
                    >
                        Lihat semua produk →
                    </a>
                </div>

                {{-- Profil UMKM --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-700">
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
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"
                            />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-900">
                        Profil UMKM
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Perbarui nama usaha, deskripsi, alamat, kontak,
                        logo, dan informasi rekening.
                    </p>

                    <a
                        href="{{ route('umkm.profile.edit') }}"
                        class="mt-5 inline-flex text-sm font-semibold text-violet-700 hover:underline"
                    >
                        Edit profil →
                    </a>
                </div>
            </div>
        </section>

        {{-- Pesanan terbaru --}}
        <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col justify-between gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Pesanan Terbaru
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Pesanan terbaru yang berisi produk milik UMKM Anda.
                    </p>
                </div>

                @if (
                    $umkm->verification_status === 'active'
                    && Route::has('umkm.orders.index')
                )
                    <a
                        href="{{ route('umkm.orders.index') }}"
                        class="text-sm font-semibold text-violet-700 transition hover:underline"
                    >
                        Lihat semua pesanan →
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Nomor Pesanan
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Customer
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Total Produk UMKM
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($latestOrders as $order)
                            @php
                                $orderStatus = $orderStatuses[$order->order_status]
                                    ?? [
                                        'label' => ucfirst($order->order_status),
                                        'class' => 'bg-slate-100 text-slate-700',
                                    ];
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="font-mono text-sm font-semibold text-slate-900">
                                        {{ $order->order_number }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $order->user?->name ?? 'Customer' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $order->recipient_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">
                                    Rp{{ number_format(
                                        (float) ($order->umkm_total ?? 0),
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $orderStatus['class'] }}">
                                        {{ $orderStatus['label'] }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    @if (
                                        $umkm->verification_status === 'active'
                                        && Route::has('umkm.orders.show')
                                    )
                                        <a
                                            href="{{ route('umkm.orders.show', $order) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-800"
                                        >
                                            Lihat Detail
                                        </a>
                                    @else
                                        <span class="inline-flex rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500">
                                            Tidak tersedia
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-6 py-14 text-center"
                                >
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="h-7 w-7"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.064v6.75a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25v-6.75c0-.936.616-1.78 1.5-2.064"
                                            />
                                        </svg>
                                    </div>

                                    <p class="mt-4 font-semibold text-slate-700">
                                        Belum ada pesanan masuk
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Pesanan akan tampil setelah pembayaran
                                        customer diterima Admin.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
