<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $lowStockLimit = (int) setting('low_stock_threshold', 10);

        $lowStocks = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                's.store_id',
                's.product_id',
                'p.product_name',
                'st.store_name',
                's.quantity'
            )
            ->where('s.quantity', '<=', $lowStockLimit)
            ->orderBy('s.quantity', 'asc')
            ->limit(5)
            ->get();

        $pendingOrders = DB::table('sales.orders as o')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                o.order_id,
                o.order_date,
                o.status,
                CONCAT(c.first_name, " ", c.last_name) as customer_name
            ')
            ->where('o.status', 'Pending')
            ->orderByDesc('o.order_id')
            ->limit(5)
            ->get();

        $riskFlags = DB::table('risk_flags')
            ->where('status', 'Open')
            ->orderByRaw("
                CASE
                    WHEN severity = 'High' THEN 1
                    WHEN severity = 'Medium' THEN 2
                    ELSE 3
                END
            ")
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $lowStockCount = DB::table('production.stocks')
            ->where('quantity', '<=', $lowStockLimit)
            ->count();

        $pendingOrderCount = DB::table('sales.orders')
            ->where('status', 'Pending')
            ->count();

        $riskFlagCount = DB::table('risk_flags')
            ->where('status', 'Open')
            ->count();

        return response()->json([
            'total' => $lowStockCount + $pendingOrderCount + $riskFlagCount,
            'low_stock_count' => $lowStockCount,
            'pending_order_count' => $pendingOrderCount,
            'risk_flag_count' => $riskFlagCount,
            'low_stocks' => $lowStocks,
            'pending_orders' => $pendingOrders,
            'risk_flags' => $riskFlags,
        ]);
    }
}