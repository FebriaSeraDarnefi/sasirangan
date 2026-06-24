@extends('layouts.dashboard')

@section('title', 'Kelola UMKM')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-medium text-violet-600">
            Administrasi
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Kelola UMKM
        </h1>

        <p class="mt-2 text-slate-500">
            Verifikasi dan pantau UMKM yang bergabung di SasiVerse.
        </p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            UMKM
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Pemilik
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($umkms as $umkm)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900">
                                    {{ $umkm->business_name }}
                                </p>

                                <p class="text-sm text-slate-500">
                                    {{ $umkm->user->email }}
                                </p>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $umkm->owner_name }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match ($umkm->verification_status) {
                                        'active' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp

                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($umkm->verification_status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('admin.umkms.show', $umkm) }}"
                                    class="font-semibold text-violet-700 hover:underline"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-slate-500"
                            >
                                Belum ada data UMKM.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-6 py-4">
            {{ $umkms->links() }}
        </div>
    </div>
@endsection