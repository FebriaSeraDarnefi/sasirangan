@extends('layouts.dashboard')

@section('title', 'QR Code Produk')

@section('content')
    <style>
        @media print {
            body {
                background: white !important;
            }

            .print-hidden {
                display: none !important;
            }

            .print-card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 auto !important;
            }
        }
    </style>

    <div class="print-hidden mb-8">
        <a
            href="{{ route('umkm.products.index') }}"
            class="text-sm font-semibold text-violet-700 hover:underline"
        >
            ← Kembali ke daftar produk
        </a>

        <h1 class="mt-4 text-3xl font-bold text-slate-900">
            QR Code Produk
        </h1>

        <p class="mt-2 text-slate-500">
            Cetak atau unduh identitas digital produk.
        </p>
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="print-card mx-auto max-w-xl rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-700 text-xl font-bold text-white">
                    SV
                </div>

                <p class="mt-5 text-sm font-semibold uppercase tracking-[0.25em] text-violet-700">
                    SasiVerse
                </p>

                <h2 class="mt-3 text-2xl font-bold text-slate-900">
                    {{ $product->name }}
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    {{ $product->umkm->business_name }}
                </p>

                <div class="mx-auto mt-7 w-full max-w-xs rounded-3xl border border-slate-200 bg-white p-5">
                    <img
                        src="{{ route('store.product.qr', [
                            'product' => $product->slug,
                        ]) }}"
                        alt="QR Code {{ $product->name }}"
                        class="mx-auto aspect-square w-full"
                    >
                </div>

                <p class="mt-6 text-sm text-slate-500">
                    Pindai QR Code untuk melihat informasi produk
                </p>

                <p class="mt-3 font-mono text-lg font-bold tracking-wider text-slate-900">
                    {{ $product->upc }}
                </p>

                <div class="mx-auto mt-6 max-w-md rounded-2xl bg-slate-50 px-5 py-4">
                    <p class="break-all text-xs leading-5 text-slate-500">
                        {{ $productUrl }}
                    </p>
                </div>

                <div class="mt-7 border-t border-dashed border-slate-300 pt-5">
                    <p class="text-xs leading-5 text-slate-500">
                        Produk terdaftar di SasiVerse sebagai produk
                        Sasirangan dari UMKM terverifikasi.
                    </p>
                </div>
            </div>
        </div>

        <aside class="print-hidden space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Informasi Produk
                </h2>

                <dl class="mt-5 space-y-4">
                    <div>
                        <dt class="text-sm text-slate-500">
                            Nama produk
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $product->name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Kode produk
                        </dt>

                        <dd class="mt-1 font-mono font-semibold text-slate-900">
                            {{ $product->upc }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            Motif
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $product->motif_name ?: '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">
                            UMKM
                        </dt>

                        <dd class="mt-1 font-semibold text-slate-900">
                            {{ $product->umkm->business_name }}
                        </dd>
                    </div>
                </dl>
            </div>

            <button
                type="button"
                onclick="window.print()"
                class="w-full rounded-xl bg-violet-700 px-5 py-3 font-semibold text-white transition hover:bg-violet-800"
            >
                Cetak Label
            </button>

            <a
                href="{{ route('umkm.products.qr.download', $product) }}"
                class="flex w-full items-center justify-center rounded-xl border border-violet-200 bg-violet-50 px-5 py-3 font-semibold text-violet-700 transition hover:bg-violet-100"
            >
                Unduh QR Code SVG
            </a>

            <a
                href="{{ route('store.product.show', [
                    'product' => $product->slug,
                ]) }}"
                target="_blank"
                class="flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Buka Halaman Produk
            </a>
        </aside>
    </div>
@endsection