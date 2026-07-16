<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Umkm;
use App\Models\User;
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

        $statistics = [
            'customers' => User::query()
                ->where('role', 'customer')
                ->count(),

            'umkms' => User::query()
                ->where('role', 'umkm')
                ->count(),

            'pending_umkms' => Umkm::query()
                ->where('verification_status', 'pending')
                ->count(),

            'products' => Product::query()->count(),

            'orders' => Order::query()->count(),

            'waiting_payments' => Payment::query()
                ->where('status', 'waiting')
                ->count(),
        ];

        $visitorAnalytics = $analyticsService->marketplace(
            $visitorPeriod,
            $visitorStart,
            $visitorEnd
        );

        $latestOrders = Order::query()
            ->with([
                'user',
                'payment',
            ])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.admin', compact(
            'statistics',
            'visitorAnalytics',
            'latestOrders'
        ));
    }
}