<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectUmkmRequest;
use App\Models\Umkm;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UmkmVerificationController extends Controller
{
    public function index(): View
    {
        $umkms = Umkm::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.umkms.index', compact('umkms'));
    }

    public function show(Umkm $umkm): View
    {
        $umkm->load('user');

        return view('admin.umkms.show', compact('umkm'));
    }

    public function approve(Umkm $umkm): RedirectResponse
    {
        $umkm->update([
            'verification_status' => 'active',
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('admin.umkms.index')
            ->with(
                'success',
                "UMKM {$umkm->business_name} berhasil disetujui."
            );
    }

    public function reject(
        RejectUmkmRequest $request,
        Umkm $umkm
    ): RedirectResponse {
        $umkm->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->validated(
                'rejection_reason'
            ),
        ]);

        return redirect()
            ->route('admin.umkms.index')
            ->with(
                'success',
                "UMKM {$umkm->business_name} berhasil ditolak."
            );
    }

    public function deactivate(Umkm $umkm): RedirectResponse
    {
        $umkm->update([
            'verification_status' => 'inactive',
        ]);

        return redirect()
            ->route('admin.umkms.index')
            ->with(
                'success',
                "UMKM {$umkm->business_name} berhasil dinonaktifkan."
            );
    }
}
