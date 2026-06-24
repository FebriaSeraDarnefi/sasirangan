@extends('layouts.dashboard')

@section('title', 'Kelola Produk')

@section('content')
    {{-- Header --}}
    <div class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                Produk UMKM
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Kelola Produk
            </h1>

            <p class="mt-2 text-slate-500">
                Kelola produk Sasirangan milik
                <span class="font-semibold text-slate-700">
                    {{ $umkm->business_name }}
                </span>.
            </p>
        </div>

        @if ($umkm->verification_status === 'active')
            <a
                href="{{ route('umkm.products.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-800"
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
                        d="M12 6v12m6-6H6"
                    />
                </svg>

                Tambah Produk
            </a>
        @endif
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if (session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Status UMKM --}}
    @if ($umkm->verification_status !== 'active')
        <div class="mb-7 rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-800">
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
                            d="M12 9v3.75m0 3.75h.008v.008H12v-.008Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.34 3.94 2.66 17.25A2.25 2.25 0 0 0 4.61 20.63h14.78a2.25 2.25 0 0 0 1.95-3.38L13.66 3.94a1.92 1.92 0 0 0-3.32 0Z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="font-bold">
                        UMKM belum aktif
                    </p>

                    <p class="mt-1 text-sm leading-6">
                        Produk baru dapat ditambahkan setelah pendaftaran
                        UMKM disetujui oleh Admin.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistik produk --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
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
                Semua Produk
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ number_format($statistics['total'] ?? 0) }}
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

            <p class="mt-2 text-3xl font-bold text-green-800">
                {{ number_format($statistics['active'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-100 p-5 shadow-sm">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-200 text-slate-700">
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

            <p class="mt-5 text-sm text-slate-600">
                Produk Nonaktif
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-800">
                {{ number_format($statistics['inactive'] ?? 0) }}
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
                        d="M12 9v3.75m0 3.75h.008v.008H12v-.008Z"
                    />

                    <circle cx="12" cy="12" r="9"></circle>
                </svg>
            </div>

            <p class="mt-5 text-sm text-red-700">
                Stok Habis
            </p>

            <p class="mt-2 text-3xl font-bold text-red-800">
                {{ number_format($statistics['out_of_stock'] ?? 0) }}
            </p>
        </div>
    </div>

    {{-- Daftar produk --}}
    <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Daftar Produk
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Semua produk yang dimiliki oleh UMKM Anda.
                    </p>
                </div>

                <span class="w-fit rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violet-700">
                    {{ number_format($statistics['total'] ?? 0) }} produk
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Produk
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            UPC
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Harga
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Stok
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr class="transition hover:bg-slate-50">
                            {{-- Produk --}}
                            <td class="px-6 py-4">
                                <div class="flex min-w-64 items-center gap-4">
                                    @if ($product->main_image)
                                        <img
                                            src="{{ asset('storage/'.$product->main_image) }}"
                                            alt="{{ $product->name }}"
                                            class="h-16 w-16 shrink-0 rounded-xl border border-slate-200 object-cover"
                                        >
                                    @else
                                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-xl font-bold text-violet-700">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $product->name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $product->motif_name ?: 'Tanpa motif' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ number_format($product->images_count ?? 0) }}
                                            foto tambahan
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- UPC --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="rounded-lg bg-slate-100 px-3 py-2 font-mono text-xs font-semibold text-slate-700">
                                    {{ $product->upc }}
                                </span>
                            </td>

                            {{-- Harga --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">
                                Rp{{ number_format(
                                    (float) $product->price,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            {{-- Stok --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($product->stock <= 0)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Habis
                                    </span>
                                @elseif ($product->stock <= 5)
                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        {{ $product->stock }} tersisa
                                    </span>
                                @else
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ number_format($product->stock) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($product->status === 'active')
                                    <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex flex-wrap justify-end gap-2">
                                    @if ($product->status === 'active')
                                        <a
                                            href="{{ route('store.product.show', [
                                                'product' => $product->slug,
                                            ]) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"
                                        >
                                            Lihat
                                        </a>
                                    @endif

                                    <a
                                        href="{{ route('umkm.products.qr.show', $product) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-100"
                                    >
                                        QR Code
                                    </a>

                                    <a
                                        href="{{ route('umkm.products.edit', $product) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-violet-50 px-3 py-2 text-xs font-semibold text-violet-700 transition hover:bg-violet-100"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('umkm.products.destroy', $product) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus produk {{ addslashes($product->name) }}?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="px-6 py-16 text-center"
                            >
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="h-8 w-8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 6.75h16.5v13.5H3.75V6.75Zm3-3h10.5v3H6.75v-3Z"
                                        />
                                    </svg>
                                </div>

                                <h3 class="mt-5 text-lg font-bold text-slate-900">
                                    Belum ada produk
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">
                                    Tambahkan produk Sasirangan pertama untuk
                                    mulai ditampilkan di SasiVerse.
                                </p>

                                @if ($umkm->verification_status === 'active')
                                    <a
                                        href="{{ route('umkm.products.create') }}"
                                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-800"
                                    >
                                        Tambah Produk
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection

