<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\ProductVisitAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        ProductVisitAnalyticsService $analyticsService
    ): View {
        $validated = $request->validate([
            'visitor_period' => [
                'nullable',
                'in:day,week,month,year,custom',
            ],
            'visitor_start' => [
                'nullable',
                'required_if:visitor_period,custom',
                'date_format:Y-m-d',
            ],
            'visitor_end' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:visitor_start',
            ],
        ], [
            'visitor_start.required_if' => 'Pilih tanggal mulai terlebih dahulu.',
            'visitor_start.date_format' => 'Format tanggal mulai tidak valid.',
            'visitor_end.date_format' => 'Format tanggal akhir tidak valid.',
            'visitor_end.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.',
        ]);

        $visitorPeriod = $analyticsService->normalizePeriod(
            $validated['visitor_period'] ?? null
        );

        $visitorStart = $validated['visitor_start'] ?? null;
        $visitorEnd = $validated['visitor_end'] ?? null;

        $user = auth()->user();
        $umkm = $user->umkm;

        if (! $umkm) {
            return view('dashboards.umkm', [
                'umkm' => null,
                'statistics' => [],
                'visitorAnalytics' => $analyticsService->empty(
                    $visitorPeriod,
                    $visitorStart,
                    $visitorEnd
                ),
                'latestOrders' => collect(),
            ]);
        }

        $statistics = [
            'products' => $umkm->products()->count(),

            'active_products' => $umkm->products()
                ->where('status', 'active')
                ->count(),

            'out_of_stock' => $umkm->products()
                ->where('stock', 0)
                ->count(),

            'orders' => Order::whereHas('items', function ($query) use ($umkm) {
                $query->where('umkm_id', $umkm->id);
            })->count(),

            'paid_revenue' => OrderItem::where('umkm_id', $umkm->id)
                ->whereHas('order', function ($query) {
                    $query->where('payment_status', 'paid');
                })
                ->sum('subtotal'),
        ];

        $visitorAnalytics = $analyticsService->forUmkm(
            $umkm,
            $visitorPeriod,
            $visitorStart,
            $visitorEnd
        );

        $latestOrders = Order::with('user')
            ->whereHas('items', function ($query) use ($umkm) {
                $query->where('umkm_id', $umkm->id);
            })
            ->withSum([
                'items as umkm_total' => function ($query) use ($umkm) {
                    $query->where('umkm_id', $umkm->id);
                },
            ], 'subtotal')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.umkm', compact(
            'umkm',
            'statistics',
            'visitorAnalytics',
            'latestOrders'
        ));
    }
}