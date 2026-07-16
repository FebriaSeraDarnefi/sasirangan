<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Data demo hanya untuk localhost
        |--------------------------------------------------------------------------
        |
        | Di Railway/production, seeder ini tidak akan memasukkan ulang akun
        | dan produk demo setiap aplikasi melakukan deployment.
        |
        */
        if (! app()->environment('production')) {
            $this->call([
                SasiVerseSeeder::class,
            ]);
        }
    }
}