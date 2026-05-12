<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/products', function () {

    return DB::table('production.products')
        ->get();

});

Route::get('/orders', function () {

    return DB::table('sales.orders')
        ->get();

});

Route::get('/customers', function () {

    return DB::table('sales.customers')
        ->get();

});