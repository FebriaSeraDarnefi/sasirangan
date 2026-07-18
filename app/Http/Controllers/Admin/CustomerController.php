<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(
        Request $request
    ): View {
        $validated = $request->validate([
            'q' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'nullable',
                'in:active,inactive',
            ],
        ]);

        $keyword = trim(
            $validated['q'] ?? ''
        );

        $status = $validated['status'] ?? null;

        $customers = User::query()
            ->where('role', 'customer')

            /*
            |--------------------------------------------------------------------------
            | Menghitung jumlah pesanan customer
            |--------------------------------------------------------------------------
            */

            ->withCount('orders')

            /*
            |--------------------------------------------------------------------------
            | Menghitung total nilai transaksi
            |--------------------------------------------------------------------------
            */

            ->withSum(
                'orders as total_spent',
                'total_amount'
            )

            /*
            |--------------------------------------------------------------------------
            | Pencarian customer
            |--------------------------------------------------------------------------
            */

            ->when(
                $keyword !== '',
                function ($query) use (
                    $keyword
                ): void {
                    $query->where(
                        function ($customerQuery) use (
                            $keyword
                        ): void {
                            $customerQuery
                                ->where(
                                    'name',
                                    'like',
                                    "%{$keyword}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$keyword}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$keyword}%"
                                )
                                ->orWhere(
                                    'address',
                                    'like',
                                    "%{$keyword}%"
                                );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Filter status akun
            |--------------------------------------------------------------------------
            */

            ->when(
                filled($status),
                fn ($query) => $query->where(
                    'status',
                    $status
                )
            )

            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Ringkasan customer
        |--------------------------------------------------------------------------
        */

        $customerSummary = [
            'total' => User::query()
                ->where('role', 'customer')
                ->count(),

            'active' => User::query()
                ->where('role', 'customer')
                ->where('status', 'active')
                ->count(),

            'inactive' => User::query()
                ->where('role', 'customer')
                ->where('status', 'inactive')
                ->count(),

            'new_this_month' => User::query()
                ->where('role', 'customer')
                ->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ])
                ->count(),
        ];

        return view(
            'admin.customers.index',
            compact(
                'customers',
                'customerSummary',
                'keyword',
                'status'
            )
        );
    }
}