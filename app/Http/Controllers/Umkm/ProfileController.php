<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUmkmProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();
        $umkm = $user?->umkm;

        abort_if(
            $umkm === null,
            404,
            'Profil UMKM tidak ditemukan.'
        );

        return view('umkm.profile.edit', compact('umkm'));
    }

    public function update(
        UpdateUmkmProfileRequest $request
    ): RedirectResponse {
        $user = $request->user();
        $umkm = $user->umkm;

        abort_if(
            $umkm === null,
            404,
            'Profil UMKM tidak ditemukan.'
        );

        $validated = $request->validated();

        $oldLogoPath = $umkm->logo;
        $newLogoPath = null;

        /*
        |--------------------------------------------------------------------------
        | Upload logo baru
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('logo')) {
            $newLogoPath = $request
                ->file('logo')
                ->store('umkm/logos', 'public');

            $validated['logo'] = $newLogoPath;
        } else {
            unset($validated['logo']);
        }

        try {
            DB::transaction(function () use (
                $user,
                $umkm,
                $validated
            ): void {
                /*
                |--------------------------------------------------------------------------
                | Perbarui profil UMKM
                |--------------------------------------------------------------------------
                */
                $umkm->update($validated);

                /*
                |--------------------------------------------------------------------------
                | Samakan informasi pada tabel users
                |--------------------------------------------------------------------------
                */
                $user->update([
                    'name' => $validated['owner_name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                ]);
            });
        } catch (Throwable $exception) {
            /*
            | Hapus logo baru jika proses database gagal.
            */
            if ($newLogoPath !== null) {
                Storage::disk('public')->delete($newLogoPath);
            }

            throw $exception;
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus logo lama setelah pembaruan berhasil
        |--------------------------------------------------------------------------
        */
        if (
            $newLogoPath !== null
            && $oldLogoPath !== null
            && $oldLogoPath !== $newLogoPath
        ) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        return redirect()
            ->route('umkm.profile.edit')
            ->with(
                'success',
                'Profil UMKM berhasil diperbarui.'
            );
    }
}