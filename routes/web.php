<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/login');

})->name('logout');

Route::get(
    '/stocks/{store_id}/{product_id}/edit',
    [StockController::class, 'edit']
);

Route::post(
    '/stocks/update',
    [StockController::class, 'update']
);

Route::post(
    '/orders/{id}/pay',
    [PaymentController::class, 'pay']
);

Route::middleware(['auth'])->group(function () {

    // DASHBOARD

    Route::get('/',
    [DashboardController::class, 'index']
)->name('dashboard');

    // PRODUCTS

    Route::resource(
        'products',
        ProductController::class
    );

    // ORDERS

    Route::resource(
        'orders',
        OrderController::class
    );

    // CUSTOMERS

    Route::resource(
        'customers',
        CustomerController::class
    );

    // STOCKS

    Route::get(
        '/stocks',
        [StockController::class, 'index']
    );

});

require __DIR__.'/auth.php';