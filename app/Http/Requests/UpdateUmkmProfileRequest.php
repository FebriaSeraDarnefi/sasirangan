<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUmkmProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'umkm';
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'phone' => ['required', 'string', 'max:20'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'business_name.required' => 'Nama UMKM wajib diisi.',
            'owner_name.required' => 'Nama pemilik wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, PNG, atau WebP.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
        ];
    }
}
