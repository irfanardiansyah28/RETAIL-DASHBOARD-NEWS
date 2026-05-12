<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {

        // TOTAL PRODUCTS

        $totalProducts = DB::table(
            'production.products'
        )->count();

        // TOTAL CUSTOMERS

        $totalCustomers = DB::table(
            'sales.customers'
        )->count();

        // TOTAL ORDERS

        $totalOrders = DB::table(
            'sales.orders'
        )->count();

        // TOTAL REVENUE

        $totalRevenue = DB::table(
            'sales.order_items'
        )

        ->selectRaw('
            SUM(
                quantity *
                list_price *
                (1-discount/100)
            ) as revenue
        ')

        ->value('revenue');

        // MONTHLY ORDERS

        $monthlyOrders = DB::table(
            'sales.orders'
        )

        ->selectRaw('
            MONTH(order_date) as month,
            COUNT(*) as total
        ')

        ->groupBy('month')

        ->orderBy('month')

        ->get();

        // TOP SELLING PRODUCTS

        $topProducts = DB::table(
            'sales.order_items as oi'
        )

        ->join(
            'production.products as p',
            'oi.product_id',
            '=',
            'p.product_id'
        )

        ->selectRaw('
            p.product_name,
            SUM(oi.quantity) as total_sold
        ')

        ->groupBy('p.product_name')

        ->orderByDesc('total_sold')

        ->limit(5)

        ->get();


        $lowStocks = DB::table(
            'production.stocks as s'
        )
        
        ->join(
            'production.products as p',
            's.product_id',
            '=',
            'p.product_id'
        )
        
        ->select(
            'p.product_name',
            's.quantity'
        )
        
        ->where(
            's.quantity',
            '<',
            10
        )
        
        ->get();

        return view(
            'dashboard',
            compact(
                'totalProducts',
                'totalCustomers',
                'totalOrders',
                'totalRevenue',
                'monthlyOrders',
                'topProducts',
                'lowStocks'
            )
        );
    }
}