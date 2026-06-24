@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('content')
    <div class="mb-8">
        <a
            href="{{ route('umkm.products.index') }}"
            class="text-sm font-semibold text-violet-700 hover:underline"
        >
            ← Kembali ke daftar produk
        </a>

        <h1 class="mt-4 text-3xl font-bold text-slate-900">
            Edit Produk
        </h1>

        <p class="mt-2 text-slate-500">
            Perbarui informasi {{ $product->name }}.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('umkm.products.update', $product) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        @include('umkm.products._form', [
            'product' => $product,
        ])
    </form>
@endsection