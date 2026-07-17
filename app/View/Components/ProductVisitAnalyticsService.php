<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductView;
use App\Models\Umkm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductVisitAnalyticsService
{
    private const DEFAULT_PERIOD = 'month';

    private const PERIODS = [
        'day' => 'Hari Ini',
        'week' => '7 Hari Terakhir',
        'month' => '30 Hari Terakhir',
        'year' => '12 Bulan Terakhir',
    ];

    /**
     * Analitik seluruh kunjungan produk untuk dashboard Admin.
     */
    public function marketplace(
        string $period = self::DEFAULT_PERIOD,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        return $this->buildAnalytics(
            ProductView::query(),
            Product::query()->with('umkm'),
            $period,
            $startDate,
            $endDate
        );
    }

    /**
     * Analitik kunjungan produk milik satu UMKM.
     */
    public function forUmkm(
        Umkm $umkm,
        string $period = self::DEFAULT_PERIOD,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $viewsQuery = ProductView::query()
            ->whereHas('product', function (Builder $query) use ($umkm) {
                $query->where('umkm_id', $umkm->id);
            });

        $productsQuery = Product::query()
            ->with('umkm')
            ->where('umkm_id', $umkm->id);

        return $this->buildAnalytics(
            $viewsQuery,
            $productsQuery,
            $period,
            $startDate,
            $endDate
        );
    }

    /**
     * Struktur nilai kosong ketika akun UMKM belum memiliki profil usaha.
     */
    public function empty(
        string $period = self::DEFAULT_PERIOD,
        ?string $startDate = null,
        ?string $endDate = null
    ): array {
        $range = $this->resolveRange(
            $period,
            $startDate,
            $endDate
        );

        return [
            'period' => $range['period'],
            'period_label' => $range['label'],
            'period_summary' => $this->periodSummary(
                $range['start'],
                $range['end'],
                $range['period']
            ),
            'period_options' => self::PERIODS,
            'custom_start' => $range['custom_start'],
            'custom_end' => $range['custom_end'],
            'total_visits' => 0,
            'unique_visitors' => 0,
            'average_daily_visits' => 0,
            'most_visited_product' => null,
            'top_products' => collect(),
            'trend' => $this->emptyTrend(
                $range['start'],
                $range['end'],
                $range['group']
            ),
        ];
    }

    public function normalizePeriod(?string $period): string
    {
        if ($period === 'custom') {
            return 'custom';
        }

        return array_key_exists((string) $period, self::PERIODS)
            ? (string) $period
            : self::DEFAULT_PERIOD;
    }

    private function buildAnalytics(
        Builder $viewsQuery,
        Builder $productsQuery,
        string $period,
        ?string $startDate,
        ?string $endDate
    ): array {
        $range = $this->resolveRange(
            $period,
            $startDate,
            $endDate
        );

        $period = $range['period'];
        $start = $range['start'];
        $end = $range['end'];

        $periodViewsQuery = (clone $viewsQuery)
            ->whereBetween('viewed_at', [$start, $end]);

        $totalVisits = (clone $periodViewsQuery)->count();

        $uniqueVisitors = (clone $periodViewsQuery)
            ->whereNotNull('session_identifier')
            ->distinct()
            ->count('session_identifier');

        $numberOfDays = max(
            1,
            $start->copy()->startOfDay()->diffInDays(
                $end->copy()->startOfDay()
            ) + 1
        );

        $averageDailyVisits = round(
            $totalVisits / $numberOfDays,
            1
        );

        $topProducts = (clone $productsQuery)
            ->whereHas('views', function (Builder $query) use ($start, $end) {
                $query->whereBetween('viewed_at', [$start, $end]);
            })
            ->withCount([
                'views as period_views_count' => function (Builder $query) use ($start, $end) {
                    $query->whereBetween('viewed_at', [$start, $end]);
                },
            ])
            ->orderByDesc('period_views_count')
            ->orderBy('name')
            ->take(5)
            ->get();

        return [
            'period' => $period,
            'period_label' => $range['label'],
            'period_summary' => $this->periodSummary(
                $start,
                $end,
                $period
            ),
            'period_options' => self::PERIODS,
            'custom_start' => $range['custom_start'],
            'custom_end' => $range['custom_end'],
            'total_visits' => $totalVisits,
            'unique_visitors' => $uniqueVisitors,
            'average_daily_visits' => $averageDailyVisits,
            'most_visited_product' => $topProducts->first(),
            'top_products' => $topProducts,
            'trend' => $this->buildTrend(
                $viewsQuery,
                $start,
                $end,
                $range['group']
            ),
        ];
    }

    /**
     * Periode bawaan dan rentang tanggal kustom.
     */
    private function resolveRange(
        string $period,
        ?string $startDate,
        ?string $endDate
    ): array {
        $period = $this->normalizePeriod($period);

        if ($period === 'custom') {
            $customStart = $this->parseDate($startDate);
            $customEnd = $this->parseDate($endDate) ?? $customStart?->copy();

            if (
                $customStart
                && $customEnd
                && $customEnd->greaterThanOrEqualTo($customStart)
            ) {
                $start = $customStart->copy()->startOfDay();
                $end = $customEnd->copy()->endOfDay();

                return [
                    'period' => 'custom',
                    'label' => 'Rentang Tanggal',
                    'start' => $start,
                    'end' => $end,
                    'group' => $this->resolveCustomGroup($start, $end),
                    'custom_start' => $start->toDateString(),
                    'custom_end' => $end->toDateString(),
                ];
            }

            $period = self::DEFAULT_PERIOD;
        }

        $now = now();

        $range = match ($period) {
            'day' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'group' => 'hour',
            ],
            'week' => [
                'start' => $now->copy()->subDays(6)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'group' => 'day',
            ],
            'year' => [
                'start' => $now->copy()->subMonths(11)->startOfMonth(),
                'end' => $now->copy()->endOfDay(),
                'group' => 'month',
            ],
            default => [
                'start' => $now->copy()->subDays(29)->startOfDay(),
                'end' => $now->copy()->endOfDay(),
                'group' => 'day',
            ],
        };

        return [
            'period' => $period,
            'label' => self::PERIODS[$period],
            'start' => $range['start'],
            'end' => $range['end'],
            'group' => $range['group'],
            'custom_start' => null,
            'custom_end' => null,
        ];
    }

    private function parseDate(?string $date): ?Carbon
    {
        if (! $date) {
            return null;
        }

        try {
            $parsed = Carbon::createFromFormat(
                'Y-m-d',
                $date,
                config('app.timezone')
            );

            if ($parsed->format('Y-m-d') !== $date) {
                return null;
            }

            return $parsed;
        } catch (Throwable) {
            return null;
        }
    }

    private function resolveCustomGroup(
        Carbon $start,
        Carbon $end
    ): string {
        $days = $start->copy()->startOfDay()->diffInDays(
            $end->copy()->startOfDay()
        );

        return match (true) {
            $days === 0 => 'hour',
            $days <= 62 => 'day',
            $days <= 730 => 'month',
            default => 'year',
        };
    }

    private function buildTrend(
        Builder $viewsQuery,
        Carbon $start,
        Carbon $end,
        string $group
    ): Collection {
        [$keyExpression, $keyAlias] = match ($group) {
            'hour' => [
                "DATE_FORMAT(viewed_at, '%Y-%m-%d %H')",
                'period_key',
            ],
            'month' => [
                "DATE_FORMAT(viewed_at, '%Y-%m')",
                'period_key',
            ],
            'year' => [
                'YEAR(viewed_at)',
                'period_key',
            ],
            default => [
                'DATE(viewed_at)',
                'period_key',
            ],
        };

        $rows = (clone $viewsQuery)
            ->selectRaw("{$keyExpression} as {$keyAlias}")
            ->selectRaw('COUNT(*) as visits')
            ->selectRaw(
                'COUNT(DISTINCT session_identifier) as unique_visitors'
            )
            ->whereBetween('viewed_at', [$start, $end])
            ->groupBy(DB::raw($keyExpression))
            ->orderBy(DB::raw($keyExpression))
            ->get()
            ->keyBy(function ($row) use ($keyAlias) {
                return (string) $row->{$keyAlias};
            });

        return $this->trendSlots(
            $start,
            $end,
            $group
        )->map(function (array $slot) use ($rows) {
            $row = $rows->get((string) $slot['key']);

            return [
                'key' => $slot['key'],
                'label' => $slot['label'],
                'full_label' => $slot['full_label'],
                'visits' => (int) ($row?->visits ?? 0),
                'unique_visitors' => (int) (
                    $row?->unique_visitors ?? 0
                ),
            ];
        });
    }

    private function emptyTrend(
        Carbon $start,
        Carbon $end,
        string $group
    ): Collection {
        return $this->trendSlots(
            $start,
            $end,
            $group
        )->map(function (array $slot) {
            return [
                'key' => $slot['key'],
                'label' => $slot['label'],
                'full_label' => $slot['full_label'],
                'visits' => 0,
                'unique_visitors' => 0,
            ];
        });
    }

    private function trendSlots(
        Carbon $start,
        Carbon $end,
        string $group
    ): Collection {
        if ($group === 'hour') {
            return collect(range(0, 23))->map(function (int $hour) use ($start) {
                $date = $start->copy()->startOfDay()->addHours($hour);

                return [
                    'key' => $date->format('Y-m-d H'),
                    'label' => $date->format('H:i'),
                    'full_label' => $date->translatedFormat(
                        'd M Y, H:i'
                    ),
                ];
            });
        }

        if ($group === 'month') {
            $monthCount = $start->copy()->startOfMonth()->diffInMonths(
                $end->copy()->startOfMonth()
            );

            return collect(range(0, $monthCount))->map(function (int $month) use ($start) {
                $date = $start->copy()->startOfMonth()->addMonths($month);

                return [
                    'key' => $date->format('Y-m'),
                    'label' => $date->translatedFormat('M y'),
                    'full_label' => $date->translatedFormat('F Y'),
                ];
            });
        }

        if ($group === 'year') {
            $yearCount = $start->copy()->startOfYear()->diffInYears(
                $end->copy()->startOfYear()
            );

            return collect(range(0, $yearCount))->map(function (int $year) use ($start) {
                $date = $start->copy()->startOfYear()->addYears($year);

                return [
                    'key' => $date->format('Y'),
                    'label' => $date->format('Y'),
                    'full_label' => $date->format('Y'),
                ];
            });
        }

        $days = $start->copy()->startOfDay()->diffInDays(
            $end->copy()->startOfDay()
        );

        return collect(range(0, $days))->map(function (int $day) use ($start) {
            $date = $start->copy()->startOfDay()->addDays($day);

            return [
                'key' => $date->toDateString(),
                'label' => $date->translatedFormat('d M'),
                'full_label' => $date->translatedFormat('d F Y'),
            ];
        });
    }

    private function periodSummary(
        Carbon $start,
        Carbon $end,
        string $period
    ): string {
        if (
            $period === 'day'
            || ($period === 'custom' && $start->isSameDay($end))
        ) {
            return $start->translatedFormat('d F Y');
        }

        if ($period === 'year') {
            return $start->translatedFormat('M Y')
                .' – '
                .$end->translatedFormat('M Y');
        }

        return $start->translatedFormat('d M Y')
            .' – '
            .$end->translatedFormat('d M Y');
    }
}