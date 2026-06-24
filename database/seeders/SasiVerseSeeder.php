<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SasiVerseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            /*
            |--------------------------------------------------------------------------
            | Akun Admin
            |--------------------------------------------------------------------------
            */

            $admin = User::firstOrNew([
                'email' => 'admin@sasiverse.test',
            ]);

            $admin->forceFill([
                'name' => 'Administrator SasiVerse',
                'phone' => '081234567890',
                'address' => 'Banjarmasin, Kalimantan Selatan',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password'),
            ])->save();

            /*
            |--------------------------------------------------------------------------
            | Akun UMKM
            |--------------------------------------------------------------------------
            */

            $umkmUser = User::firstOrNew([
                'email' => 'umkm@sasiverse.test',
            ]);

            $umkmUser->forceFill([
                'name' => 'NEFI SASIRANGAN',
                'phone' => '081234567891',
                'address' => 'Banjarmasin, Kalimantan Selatan',
                'role' => 'umkm',
                'status' => 'active',
                'password' => Hash::make('password'),
            ])->save();

            /*
            |--------------------------------------------------------------------------
            | Profil UMKM
            |--------------------------------------------------------------------------
            */

            $umkm = Umkm::updateOrCreate(
                [
                    'user_id' => $umkmUser->id,
                ],
                [
                    'business_name' => 'Sasirangan NEFI SASIRANGAN',
                    'owner_name' => 'NEFI SASIRANGAN',
                    'description' => 'UMKM pengrajin kain Sasirangan khas Kalimantan Selatan.',
                    'phone' => '081234567891',
                    'whatsapp' => '6281234567891',
                    'address' => 'Banjarmasin, Kalimantan Selatan',
                    'logo' => null,
                    'bank_name' => 'Bank BRI',
                    'bank_account_number' => '1234567890',
                    'bank_account_name' => 'NEFI SASIRANGAN',
                    'verification_status' => 'active',
                    'rejection_reason' => null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Akun Customer
            |--------------------------------------------------------------------------
            */

            $customer = User::firstOrNew([
                'email' => 'customer@sasiverse.test',
            ]);

            $customer->forceFill([
                'name' => 'Customer SasiVerse',
                'phone' => '081234567892',
                'address' => 'Banjarbaru, Kalimantan Selatan',
                'role' => 'customer',
                'status' => 'active',
                'password' => Hash::make('password'),
            ])->save();

            /*
            |--------------------------------------------------------------------------
            | Data Produk Sasirangan
            |--------------------------------------------------------------------------
            */

            $products = [
                [
                    'upc' => 'SVR-2026-000001',
                    'name' => 'Kain Sasirangan Motif Gigi Haruan',
                    'price' => 275000,
                    'stock' => 15,
                    'description' => 'Kain Sasirangan motif Gigi Haruan dengan pewarnaan tradisional.',
                    'size' => '2 meter x 1,15 meter',
                    'material' => 'Katun',
                    'motif_name' => 'Gigi Haruan',
                    'motif_philosophy' => 'Melambangkan ketajaman pikiran dan semangat dalam menjalani kehidupan.',
                    'color_philosophy' => 'Warna biru melambangkan ketenangan, kepercayaan, dan kedamaian.',
                    'view_count' => 25,
                ],
                [
                    'upc' => 'SVR-2026-000002',
                    'name' => 'Kain Sasirangan Motif Bayam Raja',
                    'price' => 300000,
                    'stock' => 10,
                    'description' => 'Kain Sasirangan motif Bayam Raja dengan perpaduan warna hijau dan emas.',
                    'size' => '2 meter x 1,15 meter',
                    'material' => 'Katun Premium',
                    'motif_name' => 'Bayam Raja',
                    'motif_philosophy' => 'Melambangkan kewibawaan, kehormatan, dan kedudukan yang mulia.',
                    'color_philosophy' => 'Warna hijau melambangkan kesuburan dan kehidupan.',
                    'view_count' => 40,
                ],
                [
                    'upc' => 'SVR-2026-000003',
                    'name' => 'Kain Sasirangan Motif Kambang Tanjung',
                    'price' => 325000,
                    'stock' => 8,
                    'description' => 'Kain Sasirangan bermotif Kambang Tanjung yang dibuat secara manual.',
                    'size' => '2 meter x 1,15 meter',
                    'material' => 'Sutra',
                    'motif_name' => 'Kambang Tanjung',
                    'motif_philosophy' => 'Melambangkan keindahan, keramahan, dan kelembutan.',
                    'color_philosophy' => 'Warna ungu melambangkan kemuliaan dan keanggunan.',
                    'view_count' => 32,
                ],
                [
                    'upc' => 'SVR-2026-000004',
                    'name' => 'Kemeja Sasirangan Motif Kulat Karikit',
                    'price' => 250000,
                    'stock' => 12,
                    'description' => 'Kemeja Sasirangan pria dengan motif Kulat Karikit.',
                    'size' => 'M, L, XL',
                    'material' => 'Katun',
                    'motif_name' => 'Kulat Karikit',
                    'motif_philosophy' => 'Melambangkan kemampuan untuk bertahan dan tumbuh dalam berbagai keadaan.',
                    'color_philosophy' => 'Warna cokelat melambangkan kestabilan dan kesederhanaan.',
                    'view_count' => 56,
                ],
                [
                    'upc' => 'SVR-2026-000005',
                    'name' => 'Blouse Sasirangan Motif Ombak Sinapur Karang',
                    'price' => 285000,
                    'stock' => 9,
                    'description' => 'Blouse wanita berbahan nyaman dengan motif Sasirangan tradisional.',
                    'size' => 'S, M, L, XL',
                    'material' => 'Katun Rayon',
                    'motif_name' => 'Ombak Sinapur Karang',
                    'motif_philosophy' => 'Melambangkan kekuatan dan keteguhan dalam menghadapi tantangan kehidupan.',
                    'color_philosophy' => 'Warna merah melambangkan keberanian dan semangat.',
                    'view_count' => 48,
                ],
                [
                    'upc' => 'SVR-2026-000006',
                    'name' => 'Selendang Sasirangan Motif Hiris Pudak',
                    'price' => 175000,
                    'stock' => 20,
                    'description' => 'Selendang Sasirangan motif Hiris Pudak dengan warna yang elegan.',
                    'size' => '180 cm x 50 cm',
                    'material' => 'Katun Rayon',
                    'motif_name' => 'Hiris Pudak',
                    'motif_philosophy' => 'Melambangkan nilai kebaikan, kesucian, dan manfaat bagi sesama.',
                    'color_philosophy' => 'Warna kuning melambangkan kebahagiaan dan kemakmuran.',
                    'view_count' => 21,
                ],
            ];

            foreach ($products as $productData) {
                Product::updateOrCreate(
                    [
                        'upc' => $productData['upc'],
                    ],
                    [
                        'umkm_id' => $umkm->id,
                        'name' => $productData['name'],
                        'slug' => Str::slug($productData['name']),
                        'price' => $productData['price'],
                        'stock' => $productData['stock'],
                        'description' => $productData['description'],
                        'size' => $productData['size'],
                        'material' => $productData['material'],
                        'motif_name' => $productData['motif_name'],
                        'motif_philosophy' => $productData['motif_philosophy'],
                        'color_philosophy' => $productData['color_philosophy'],
                        'main_image' => null,
                        'status' => 'active',
                        'view_count' => $productData['view_count'],
                    ]
                );
            }
        });
    }
}
