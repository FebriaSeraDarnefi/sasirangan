<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductView;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductViewDemoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Mengambil maksimal lima produk aktif
        |--------------------------------------------------------------------------
        */
        $products = Product::query()
            ->where('status', 'active')
            ->orderBy('id')
            ->take(5)
            ->get();

        if ($products->isEmpty()) {
            throw new RuntimeException(
                'Tidak ada produk aktif. Tambahkan produk terlebih dahulu.'
            );
        }

        DB::transaction(function () use ($products): void {
            /*
            |--------------------------------------------------------------------------
            | Menghapus data demo sebelumnya
            |--------------------------------------------------------------------------
            |
            | Data kunjungan asli tidak akan dihapus.
            |
            */
            $this->removePreviousDemoData();

            $productIds = $products
                ->pluck('id')
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | Membuat bobot produk
            |--------------------------------------------------------------------------
            |
            | Produk pertama akan mendapatkan kunjungan paling banyak agar
            | bagian "produk paling banyak dikunjungi" terlihat pada dashboard.
            |
            */
            $weightedProductIds = $this->makeWeightedProductPool(
                $productIds
            );

            /*
            |--------------------------------------------------------------------------
            | Pola jumlah kunjungan harian
            |--------------------------------------------------------------------------
            |
            | 8 + 9 + 10 + 11 + 12 = 50 kunjungan.
            | Pola diulang enam kali untuk 30 hari.
            |
            | Total       : 300 kunjungan
            | Rata-rata   : 10 kunjungan per hari
            |
            */
            $dailyVisitPattern = [
                8,
                9,
                10,
                11,
                12,
            ];

            $rows = [];
            $insertedPerProduct = [];
            $globalVisitNumber = 0;

            /*
            |--------------------------------------------------------------------------
            | Membuat kunjungan dari 29 hari lalu sampai hari ini
            |--------------------------------------------------------------------------
            */
            for ($dayOffset = 29; $dayOffset >= 0; $dayOffset--) {
                $day = now()
                    ->subDays($dayOffset)
                    ->startOfDay();

                $patternIndex = (29 - $dayOffset)
                    % count($dailyVisitPattern);

                $visitCount = $dailyVisitPattern[$patternIndex];

                for (
                    $visitorNumber = 1;
                    $visitorNumber <= $visitCount;
                    $visitorNumber++
                ) {
                    $productId = $weightedProductIds[
                        $globalVisitNumber
                        % count($weightedProductIds)
                    ];

                    $viewedAt = $this->randomTimeForDay($day);

                    $rows[] = [
                        'product_id' => $productId,
                        'user_id' => null,

                        /*
                        | Session dibuat berbeda untuk setiap pengunjung
                        | pada setiap tanggal.
                        */
                        'session_identifier' => sprintf(
                            'demo-visitor-%s-%02d',
                            $day->format('Ymd'),
                            $visitorNumber
                        ),

                        'viewed_at' => $viewedAt,
                        'created_at' => $viewedAt,
                        'updated_at' => $viewedAt,
                    ];

                    $insertedPerProduct[$productId] =
                        ($insertedPerProduct[$productId] ?? 0) + 1;

                    $globalVisitNumber++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Memasukkan data secara bertahap
            |--------------------------------------------------------------------------
            */
            foreach (array_chunk($rows, 100) as $chunk) {
                ProductView::query()->insert($chunk);
            }

            /*
            |--------------------------------------------------------------------------
            | Memperbarui view_count pada tabel products
            |--------------------------------------------------------------------------
            */
            foreach (
                $insertedPerProduct as $productId => $visitCount
            ) {
                Product::query()
                    ->whereKey($productId)
                    ->increment('view_count', $visitCount);
            }
        });

        $this->command?->info(
            'Data demo kunjungan berhasil dibuat.'
        );

        $this->command?->info(
            'Periode: 30 hari terakhir termasuk hari ini.'
        );

        $this->command?->info(
            'Total: 300 kunjungan, rata-rata 10 per hari.'
        );
    }

    /**
     * Menghapus data demo sebelumnya agar tidak berlipat
     * ketika seeder dijalankan kembali.
     */
    private function removePreviousDemoData(): void
    {
        $oldDemoCounts = ProductView::query()
            ->selectRaw('product_id, COUNT(*) as total')
            ->where(
                'session_identifier',
                'like',
                'demo-visitor-%'
            )
            ->groupBy('product_id')
            ->pluck('total', 'product_id');

        ProductView::query()
            ->where(
                'session_identifier',
                'like',
                'demo-visitor-%'
            )
            ->delete();

        /*
        | Mengurangi view_count dari data demo lama.
        | Data kunjungan asli tetap dipertahankan.
        */
        foreach ($oldDemoCounts as $productId => $oldCount) {
            $product = Product::query()->find($productId);

            if ($product) {
                $product->view_count = max(
                    0,
                    (int) $product->view_count
                    - (int) $oldCount
                );

                $product->save();
            }
        }
    }

    /**
     * Produk pertama mendapatkan jumlah kunjungan terbesar.
     */
    private function makeWeightedProductPool(
        array $productIds
    ): array {
        $weightsByProductCount = [
            1 => [100],
            2 => [65, 35],
            3 => [55, 30, 15],
            4 => [50, 25, 15, 10],
            5 => [45, 25, 15, 10, 5],
        ];

        $weights = $weightsByProductCount[
            count($productIds)
        ];

        $pool = [];

        foreach ($productIds as $index => $productId) {
            for ($i = 0; $i < $weights[$index]; $i++) {
                $pool[] = $productId;
            }
        }

        return $pool;
    }

    /**
     * Membuat waktu kunjungan acak antara pukul 08.00–21.59.
     */
    private function randomTimeForDay(Carbon $day): Carbon
    {
        $start = $day
            ->copy()
            ->setTime(8, 0)
            ->timestamp;

        $end = $day->isToday()
            ? max($start, now()->timestamp)
            : $day
                ->copy()
                ->setTime(21, 59, 59)
                ->timestamp;

        return Carbon::createFromTimestamp(
            random_int($start, $end),
            config('app.timezone')
        );
    }
}