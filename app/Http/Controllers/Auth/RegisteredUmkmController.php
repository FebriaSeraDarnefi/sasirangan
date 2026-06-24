<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUmkmRegistrationRequest;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUmkmController extends Controller
{
    public function create(): View
    {
        return view('auth.register-umkm');
    }

    public function store(
        StoreUmkmRegistrationRequest $request
    ): RedirectResponse {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->owner_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 'umkm',
                'status' => 'active',
                'password' => Hash::make($request->password),
            ]);

            Umkm::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'owner_name' => $request->owner_name,
                'description' => $request->description,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'verification_status' => 'pending',
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('umkm.dashboard')
            ->with(
                'success',
                'Pendaftaran UMKM berhasil. Silakan menunggu verifikasi Admin.'
            );
    }
}
