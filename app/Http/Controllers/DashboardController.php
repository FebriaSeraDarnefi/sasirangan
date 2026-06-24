<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'umkm' => redirect()->route('umkm.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => abort(403, 'Role pengguna tidak dikenali.'),
        };
    }
}
