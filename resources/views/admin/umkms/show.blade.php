@extends('layouts.dashboard')

@section('title', 'Detail UMKM')

@section('content')
    <a
        href="{{ route('admin.umkms.index') }}"
        class="text-sm font-semibold text-violet-700 hover:underline"
    >
        ← Kembali
    </a>

    <div class="mt-5 grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h1 class="text-2xl font-bold text-slate-900">
                {{ $umkm->business_name }}
            </h1>

            <dl class="mt-6 space-y-4">
                <div>
                    <dt class="text-sm text-slate-500">Nama pemilik</dt>
                    <dd class="font-semibold text-slate-900">
                        {{ $umkm->owner_name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Email</dt>
                    <dd class="font-semibold text-slate-900">
                        {{ $umkm->user->email }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Telepon</dt>
                    <dd class="font-semibold text-slate-900">
                        {{ $umkm->phone ?: '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">WhatsApp</dt>
                    <dd class="font-semibold text-slate-900">
                        {{ $umkm->whatsapp ?: '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Alamat</dt>
                    <dd class="text-slate-700">
                        {{ $umkm->address ?: '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Deskripsi</dt>
                    <dd class="whitespace-pre-line text-slate-700">
                        {{ $umkm->description ?: '-' }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-bold text-slate-900">
                Verifikasi UMKM
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Status saat ini:
                <strong>{{ ucfirst($umkm->verification_status) }}</strong>
            </p>

            @if ($umkm->rejection_reason)
                <div class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-700">
                    <strong>Alasan penolakan:</strong>

                    <p class="mt-1">{{ $umkm->rejection_reason }}</p>
                </div>
            @endif

            <div class="mt-6 space-y-4">
                @if ($umkm->verification_status !== 'active')
                    <form
                        method="POST"
                        action="{{ route('admin.umkms.approve', $umkm) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-green-600 px-4 py-3 font-semibold text-white hover:bg-green-700"
                        >
                            Setujui UMKM
                        </button>
                    </form>
                @endif

                <form
                    method="POST"
                    action="{{ route('admin.umkms.reject', $umkm) }}"
                    class="space-y-3"
                >
                    @csrf
                    @method('PATCH')

                    <textarea
                        name="rejection_reason"
                        rows="3"
                        placeholder="Tuliskan alasan penolakan"
                        class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500"
                    >{{ old('rejection_reason') }}</textarea>

                    @error('rejection_reason')
                        <p class="text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-red-600 px-4 py-3 font-semibold text-white hover:bg-red-700"
                    >
                        Tolak UMKM
                    </button>
                </form>

                @if ($umkm->verification_status === 'active')
                    <form
                        method="POST"
                        action="{{ route('admin.umkms.deactivate', $umkm) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-slate-700 px-4 py-3 font-semibold text-white hover:bg-slate-800"
                        >
                            Nonaktifkan UMKM
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection