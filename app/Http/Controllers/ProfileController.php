<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUmkmProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $umkm = auth()->user()->umkm;

        abort_if(! $umkm, 404, 'Profil UMKM tidak ditemukan.');

        return view('umkm.profile.edit', compact('umkm'));
    }

    public function update(
        UpdateUmkmProfileRequest $request
    ): RedirectResponse {
        $user = $request->user();
        $umkm = $user->umkm;

        abort_if(! $umkm, 404, 'Profil UMKM tidak ditemukan.');

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            if ($umkm->logo) {
                Storage::disk('public')->delete($umkm->logo);
            }

            $validated['logo'] = $request
                ->file('logo')
                ->store('umkm/logos', 'public');
        }

        $umkm->update($validated);

        $user->update([
            'name' => $validated['owner_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()
            ->route('umkm.profile.edit')
            ->with('success', 'Profil UMKM berhasil diperbarui.');
    }
}
