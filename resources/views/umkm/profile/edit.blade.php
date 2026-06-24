@extends('layouts.dashboard')

@section('title', 'Profil UMKM')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-medium text-violet-600">
            Pengaturan UMKM
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Profil {{ $umkm->business_name }}
        </h1>

        <p class="mt-2 text-slate-500">
            Perbarui informasi usaha, kontak, dan rekening UMKM.
        </p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <ul class="list-inside list-disc text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('umkm.profile.update') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
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
                        value="{{ old('business_name', $umkm->business_name) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        required
                    >
                </div>

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
                        value="{{ old('owner_name', $umkm->owner_name) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        required
                    >
                </div>
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
                >{{ old('description', $umkm->description) }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
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
                        value="{{ old('phone', $umkm->phone) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        required
                    >
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
                        value="{{ old('whatsapp', $umkm->whatsapp) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        required
                    >
                </div>
            </div>

            <div>
                <label
                    for="address"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Alamat
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    required
                >{{ old('address', $umkm->address) }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div>
                    <label
                        for="bank_name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nama Bank
                    </label>

                    <input
                        id="bank_name"
                        name="bank_name"
                        type="text"
                        value="{{ old('bank_name', $umkm->bank_name) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>

                <div>
                    <label
                        for="bank_account_number"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nomor Rekening
                    </label>

                    <input
                        id="bank_account_number"
                        name="bank_account_number"
                        type="text"
                        value="{{ old('bank_account_number', $umkm->bank_account_number) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>

                <div>
                    <label
                        for="bank_account_name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nama Pemilik Rekening
                    </label>

                    <input
                        id="bank_account_name"
                        name="bank_account_name"
                        type="text"
                        value="{{ old('bank_account_name', $umkm->bank_account_name) }}"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>
            </div>

            <div>
                <label
                    for="logo"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Logo UMKM
                </label>

                @if ($umkm->logo)
                    <img
                        src="{{ asset('storage/'.$umkm->logo) }}"
                        alt="{{ $umkm->business_name }}"
                        class="mb-4 h-24 w-24 rounded-xl object-cover"
                    >
                @endif

                <input
                    id="logo"
                    name="logo"
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full rounded-xl border border-slate-300 bg-white text-sm"
                >
            </div>

            <button
                type="submit"
                class="rounded-xl bg-violet-700 px-6 py-3 font-semibold text-white hover:bg-violet-800"
            >
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection