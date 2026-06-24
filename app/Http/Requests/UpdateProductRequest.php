<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $product = $this->route('product');

        return $user !== null
            && $user->role === 'umkm'
            && $user->umkm !== null
            && $user->umkm->verification_status === 'active'
            && $product instanceof Product
            && $user->umkm->id === $product->umkm_id;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'size' => [
                'nullable',
                'string',
                'max:255',
            ],

            'material' => [
                'nullable',
                'string',
                'max:255',
            ],

            'motif_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'motif_philosophy' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'color_philosophy' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'main_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],

            'images' => [
                'nullable',
                'array',
                'max:5',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],

            'delete_images' => [
                'nullable',
                'array',
            ],

            'delete_images.*' => [
                'integer',
                'exists:product_images,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'price.min' => 'Harga produk tidak boleh negatif.',

            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa bilangan bulat.',
            'stock.min' => 'Stok produk tidak boleh negatif.',

            'status.required' => 'Status produk wajib dipilih.',
            'status.in' => 'Status produk tidak valid.',

            'main_image.image' => 'Foto utama harus berupa gambar.',
            'main_image.mimes' => 'Foto utama harus berformat JPG, JPEG, PNG, atau WebP.',
            'main_image.max' => 'Ukuran foto utama maksimal 3 MB.',

            'images.array' => 'Foto tambahan tidak valid.',
            'images.max' => 'Maksimal lima foto tambahan.',

            'images.*.image' => 'Setiap foto tambahan harus berupa gambar.',
            'images.*.mimes' => 'Foto tambahan harus berformat JPG, JPEG, PNG, atau WebP.',
            'images.*.max' => 'Ukuran setiap foto tambahan maksimal 3 MB.',
        ];
    }
}
