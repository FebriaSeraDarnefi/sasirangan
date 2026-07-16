<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\UmkmVerificationController;
use App\Http\Controllers\Auth\RegisteredUmkmController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\StoreController;
use App\Http\Controllers\ProductQrCodeController;
use App\Http\Controllers\Umkm\DashboardController as UmkmDashboardController;
use App\Http\Controllers\Umkm\OrderController as UmkmOrderController;
use App\Http\Controllers\Umkm\ProductController as UmkmProductController;
use App\Http\Controllers\Umkm\ProfileController as UmkmProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Toko Publik
|--------------------------------------------------------------------------
*/

Route::get('/', [StoreController::class, 'home'])
    ->name('store.home');

Route::get('/produk', [StoreController::class, 'catalog'])
    ->name('store.catalog');

Route::get('/edukasi', [StoreController::class, 'education'])
    ->name('store.education');

Route::get('/produk/{product:slug}', [StoreController::class, 'show'])
    ->name('store.product.show');

Route::get(
    '/produk/{product:slug}/qr.svg',
    [ProductQrCodeController::class, 'image']
)->name('store.product.qr');

/*
|--------------------------------------------------------------------------
| Registrasi UMKM
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get(
        '/daftar-umkm',
        [RegisteredUmkmController::class, 'create']
    )->name('umkm.register');

    Route::post(
        '/daftar-umkm',
        [RegisteredUmkmController::class, 'store']
    )->name('umkm.register.store');
});

/*
|--------------------------------------------------------------------------
| Pengarah Dashboard Berdasarkan Role
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'umkm' => redirect()->route('umkm.dashboard'),
        'customer' => redirect()->route('customer.dashboard'),
        default => abort(403, 'Role pengguna tidak dikenali.'),
    };
})
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Keranjang, Checkout, dan Pembayaran Customer
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:customer',
])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Keranjang
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/keranjang',
        [CartController::class, 'index']
    )->name('customer.cart.index');

    Route::post(
        '/keranjang/{product}',
        [CartController::class, 'store']
    )->name('customer.cart.store');

    Route::patch(
        '/keranjang/item/{cartItem}',
        [CartController::class, 'update']
    )->name('customer.cart.update');

    Route::delete(
        '/keranjang/item/{cartItem}',
        [CartController::class, 'destroy']
    )->name('customer.cart.destroy');

    Route::delete(
        '/keranjang',
        [CartController::class, 'clear']
    )->name('customer.cart.clear');

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/checkout',
        [CheckoutController::class, 'create']
    )->name('customer.checkout.create');

    Route::post(
        '/checkout',
        [CheckoutController::class, 'store']
    )->name('customer.checkout.store');

    Route::get(
        '/checkout/berhasil/{order}',
        [CheckoutController::class, 'success']
    )->name('customer.checkout.success');

    /*
    |--------------------------------------------------------------------------
    | Pesanan Customer
    |--------------------------------------------------------------------------
    */

   Route::get(
    '/pesanan',
    [OrderController::class, 'index']
)->name('customer.orders.index');

Route::get(
    '/pesanan/{order}',
    [OrderController::class, 'show']
)
    ->whereNumber('order')
    ->name('customer.orders.show');

Route::patch(
    '/pesanan/{order}/pengiriman/{fulfillment}/diterima',
    [OrderController::class, 'completeFulfillment']
)
    ->whereNumber('order')
    ->whereNumber('fulfillment')
    ->name('customer.orders.fulfillments.complete');


    /*

    |--------------------------------------------------------------------------
    | Pembayaran Customer
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pesanan/{order}/pembayaran',
        [PaymentController::class, 'create']
    )->name('customer.payment.create');

    Route::post(
        '/pesanan/{order}/pembayaran',
        [PaymentController::class, 'store']
    )->name('customer.payment.store');
});

/*
|--------------------------------------------------------------------------
| Halaman Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth',
        'role:admin',
    ])
    ->group(function () {
        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');
        /*
        |--------------------------------------------------------------------------
        | Verifikasi Pembayaran
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/payments',
            [PaymentVerificationController::class, 'index']
        )->name('payments.index');

        Route::get(
            '/payments/{payment}',
            [PaymentVerificationController::class, 'show']
        )
            ->whereNumber('payment')
            ->name('payments.show');

        Route::patch(
            '/payments/{payment}/approve',
            [PaymentVerificationController::class, 'approve']
        )
            ->whereNumber('payment')
            ->name('payments.approve');

        Route::patch(
            '/payments/{payment}/reject',
            [PaymentVerificationController::class, 'reject']
        )
            ->whereNumber('payment')
            ->name('payments.reject');

        /*
        |--------------------------------------------------------------------------
        | Verifikasi UMKM
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/umkms',
            [UmkmVerificationController::class, 'index']
        )->name('umkms.index');

        Route::get(
            '/umkms/{umkm}',
            [UmkmVerificationController::class, 'show']
        )->name('umkms.show');

        Route::patch(
            '/umkms/{umkm}/approve',
            [UmkmVerificationController::class, 'approve']
        )->name('umkms.approve');

        Route::patch(
            '/umkms/{umkm}/reject',
            [UmkmVerificationController::class, 'reject']
        )->name('umkms.reject');

        Route::patch(
            '/umkms/{umkm}/deactivate',
            [UmkmVerificationController::class, 'deactivate']
        )->name('umkms.deactivate');
    });

/*
|--------------------------------------------------------------------------
| Halaman UMKM
|--------------------------------------------------------------------------
*/

Route::prefix('umkm')
    ->name('umkm.')
    ->middleware([
        'auth',
        'role:umkm',
    ])
    ->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Dashboard UMKM
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [UmkmDashboardController::class, 'index']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Pesanan UMKM
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/orders',
            [UmkmOrderController::class, 'index']
        )->name('orders.index');

        Route::get(
            '/orders/{order}',
            [UmkmOrderController::class, 'show']
        )
            ->whereNumber('order')
            ->name('orders.show');

        Route::patch(
            '/orders/{order}/packed',
            [UmkmOrderController::class, 'markAsPacked']
        )
            ->whereNumber('order')
            ->name('orders.packed');

        Route::patch(
            '/orders/{order}/shipped',
            [UmkmOrderController::class, 'markAsShipped']
        )
            ->whereNumber('order')
            ->name('orders.shipped');

        /*
        |--------------------------------------------------------------------------
        | Profil UMKM
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profil',
            [UmkmProfileController::class, 'edit']
        )->name('profile.edit');

        Route::put(
            '/profil',
            [UmkmProfileController::class, 'update']
        )->name('profile.update');

        /*
        |--------------------------------------------------------------------------
        | QR Code Produk
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/products/{product}/qr',
            [ProductQrCodeController::class, 'show']
        )
            ->whereNumber('product')
            ->name('products.qr.show');

        Route::get(
            '/products/{product}/qr/download',
            [ProductQrCodeController::class, 'download']
        )
            ->whereNumber('product')
            ->name('products.qr.download');

        /*
        |--------------------------------------------------------------------------
        | Pengelolaan Produk
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            UmkmProductController::class
        )->except([
            'show',
        ]);
    });

/*
|--------------------------------------------------------------------------
| Halaman Customer
|--------------------------------------------------------------------------
*/

Route::prefix('customer')
    ->name('customer.')
    ->middleware([
        'auth',
        'role:customer',
    ])
    ->group(function () {
        Route::get(
            '/dashboard',
            [CustomerDashboardController::class, 'index']
        )->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Route Autentikasi Laravel Breeze
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';