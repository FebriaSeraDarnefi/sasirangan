<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
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
            'latestOrders'
        ));
    }
}
