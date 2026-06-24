@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('content')
    <div class="mb-8">
        <a
            href="{{ route('umkm.products.index') }}"
            class="text-sm font-semibold text-violet-700 hover:underline"
        >
            ← Kembali ke daftar produk
        </a>

        <h1 class="mt-4 text-3xl font-bold text-slate-900">
            Tambah Produk
        </h1>

        <p class="mt-2 text-slate-500">
            Masukkan informasi produk Sasirangan yang akan dijual.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('umkm.products.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        @include('umkm.products._form')
    </form>
@endsection