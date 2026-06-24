<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard UMKM
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola usaha dan produk Sasirangan Anda.
                </p>
            </div>

            @if ($umkm)
                @php
                    $statusClass = match ($umkm->verification_status) {
                        'active' => 'bg-green-100 text-green-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-700',
                    };

                    $statusLabel = match ($umkm->verification_status) {
                        'active' => 'Aktif',
                        'pending' => 'Menunggu Verifikasi',
                        'rejected' => 'Ditolak',
                        'inactive' => 'Nonaktif',
                        default => ucfirst($umkm->verification_status),
                        
                    };
                @endphp

                <span class="w-fit rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

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
                                d="M11.25 9.75h1.5v5.25h-1.5V9.75Zm0 7.5h1.5v1.5h-1.5v-1.5Z"
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

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-yellow-800">
                        Akun ini belum memiliki profil UMKM. Silakan lengkapi
                        pendaftaran UMKM terlebih dahulu agar dapat mengelola
                        produk dan pesanan.
                    </p>
                </div>
            @else
                {{-- Sambutan --}}
                <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-800 to-indigo-900 p-7 text-white shadow-xl sm:p-9">
                    <div class="absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-20 right-24 h-44 w-44 rounded-full bg-violet-300/10"></div>

                    <div class="relative flex flex-col justify-between gap-7 lg:flex-row lg:items-center">
                        <div>
                            <p class="text-sm font-semibold text-violet-200">
                                Selamat datang kembali
                            </p>

                            <h1 class="mt-2 text-3xl font-bold">
                                {{ $umkm->business_name }}
                            </h1>

                            <p class="mt-3 max-w-xl text-sm leading-6 text-violet-100/80">
                                Pantau perkembangan usaha, kelola produk, dan
                                lihat aktivitas pesanan Sasirangan Anda.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
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

                @if ($umkm->verification_status !== 'active')
                    <div class="mt-6 rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-100 text-yellow-700">
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
                                        d="M12 6v6l4 2"
                                    />

                                    <circle cx="12" cy="12" r="9"></circle>
                                </svg>
                            </div>

                            <div>
                                <p class="font-semibold text-yellow-900">
                                    UMKM belum aktif
                                </p>

                                <p class="mt-1 text-sm leading-6 text-yellow-700">
                                    Anda dapat memperbarui profil, tetapi belum
                                    dapat menambahkan produk sampai pendaftaran
                                    disetujui oleh Admin.
                                </p>

                                @if ($umkm->verification_status === 'rejected' && $umkm->rejection_reason)
                                    <p class="mt-2 text-sm text-red-700">
                                        <strong>Alasan penolakan:</strong>
                                        {{ $umkm->rejection_reason }}
                                    </p>
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
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between">
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
                            </div>

                            <p class="mt-5 text-sm text-slate-500">
                                Jumlah Produk
                            </p>

                            <p class="mt-1 text-3xl font-bold text-slate-900">
                                {{ number_format($statistics['products'] ?? 0) }}
                            </p>
                        </div>

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

                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                <span class="text-lg font-bold">Rp</span>
                            </div>

                            <p class="mt-5 text-sm text-amber-700">
                                Pendapatan
                            </p>

                            <p class="mt-1 text-xl font-bold text-amber-800">
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
                <section class="mt-8 grid gap-6 lg:grid-cols-3">
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
                            Tambah Produk Baru
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Tambahkan produk Sasirangan beserta motif,
                            filosofi, harga, stok, dan foto.
                        </p>

                        @if ($umkm->verification_status === 'active')
                            <a
                                href="{{ route('umkm.products.create') }}"
                                class="mt-5 inline-flex font-semibold text-violet-700 hover:underline"
                            >
                                Tambah produk →
                            </a>
                        @else
                            <p class="mt-5 text-sm font-semibold text-yellow-700">
                                Menunggu verifikasi Admin
                            </p>
                        @endif
                    </div>

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
                                    d="M4.5 5.25h15v13.5h-15V5.25Zm3 3h9m-9 3h6"
                                />
                            </svg>
                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-900">
                            Kelola Semua Produk
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Perbarui harga, stok, foto, informasi motif, dan
                            status produk.
                        </p>

                        <a
                            href="{{ route('umkm.products.index') }}"
                            class="mt-5 inline-flex font-semibold text-violet-700 hover:underline"
                        >
                            Buka daftar produk →
                        </a>
                    </div>

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
                            Lengkapi Profil UMKM
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Perbarui informasi usaha, kontak WhatsApp,
                            alamat, logo, dan rekening bank.
                        </p>

                        <a
                            href="{{ route('umkm.profile.edit') }}"
                            class="mt-5 inline-flex font-semibold text-violet-700 hover:underline"
                        >
                            Edit profil →
                        </a>
                    </div>
                </section>

                {{-- Pesanan terbaru --}}
                <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="text-lg font-bold text-slate-900">
                            Pesanan Terbaru
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Pesanan terbaru yang berisi produk milik UMKM Anda.
                        </p>
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
                                        Total
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                        Status
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse ($latestOrders as $order)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">
                                            {{ $order->order_number }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                            {{ $order->user->name }}
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
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="4"
                                            class="px-6 py-12 text-center text-sm text-slate-500"
                                        >
                                            Belum ada pesanan masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
