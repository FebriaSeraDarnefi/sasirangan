<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $umkm = $user->umkm;

        if (! $umkm) {
            return view('dashboards.umkm', [
                'umkm' => null,
                'statistics' => [],
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
            'latestOrders'
        ));
    }
}
