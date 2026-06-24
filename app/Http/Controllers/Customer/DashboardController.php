<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $ordersQuery = Order::where('user_id', $user->id);

        $statistics = [
            'orders' => (clone $ordersQuery)->count(),

            'pending_orders' => (clone $ordersQuery)
                ->whereIn('order_status', [
                    'pending',
                    'processing',
                    'packed',
                    'shipped',
                ])
                ->count(),

            'completed_orders' => (clone $ordersQuery)
                ->where('order_status', 'completed')
                ->count(),

            'total_spent' => (clone $ordersQuery)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
        ];

        $latestOrders = (clone $ordersQuery)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.customer', compact(
            'statistics',
            'latestOrders'
        ));
    }
}
