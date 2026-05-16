<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function orderHeatmap(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $startDate = $request->start_date ?? now()->subDays(30)->toDateString();

        $endDate = $request->end_date ?? now()->toDateString();

        $ordersByDay = DB::table('sales.orders')
            ->selectRaw('
                DAYNAME(order_date) as day_name,
                DAYOFWEEK(order_date) as day_number,
                COUNT(*) as total_orders
            ')
            ->whereDate('order_date', '>=', $startDate)
            ->whereDate('order_date', '<=', $endDate)
            ->groupByRaw('
                DAYNAME(order_date),
                DAYOFWEEK(order_date)
            ')
            ->orderByRaw('DAYOFWEEK(order_date)')
            ->get();

        $ordersByHour = DB::table('sales.orders')
            ->selectRaw('
                HOUR(order_date) as hour,
                COUNT(*) as total_orders
            ')
            ->whereDate('order_date', '>=', $startDate)
            ->whereDate('order_date', '<=', $endDate)
            ->groupByRaw('HOUR(order_date)')
            ->orderByRaw('HOUR(order_date)')
            ->get();

        $revenueByDay = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->selectRaw('
                DAYNAME(o.order_date) as day_name,
                DAYOFWEEK(o.order_date) as day_number,
                SUM(
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as total_revenue
            ')
            ->whereDate('o.order_date', '>=', $startDate)
            ->whereDate('o.order_date', '<=', $endDate)
            ->groupByRaw('
                DAYNAME(o.order_date),
                DAYOFWEEK(o.order_date)
            ')
            ->orderByRaw('DAYOFWEEK(o.order_date)')
            ->get();

        $peakDay = $ordersByDay
            ->sortByDesc('total_orders')
            ->first();

        $peakHour = $ordersByHour
            ->sortByDesc('total_orders')
            ->first();

        $totalOrders = DB::table('sales.orders')
            ->whereDate('order_date', '>=', $startDate)
            ->whereDate('order_date', '<=', $endDate)
            ->count();

        $totalRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->whereDate('o.order_date', '>=', $startDate)
            ->whereDate('o.order_date', '<=', $endDate)
            ->sum(
                DB::raw('
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ')
            );

        $dayLabels = $ordersByDay
            ->pluck('day_name')
            ->toArray();

        $dayData = $ordersByDay
            ->pluck('total_orders')
            ->toArray();

        $hourLabels = $ordersByHour
            ->map(function ($item) {
                return str_pad($item->hour ?? 0, 2, '0', STR_PAD_LEFT).':00';
            })
            ->toArray();

        $hourData = $ordersByHour
            ->pluck('total_orders')
            ->toArray();

        $revenueDayLabels = $revenueByDay
            ->pluck('day_name')
            ->toArray();

        $revenueDayData = $revenueByDay
            ->pluck('total_revenue')
            ->toArray();

        return view(
            'analytics.order-heatmap',
            compact(
                'startDate',
                'endDate',
                'ordersByDay',
                'ordersByHour',
                'revenueByDay',
                'peakDay',
                'peakHour',
                'totalOrders',
                'totalRevenue',
                'dayLabels',
                'dayData',
                'hourLabels',
                'hourData',
                'revenueDayLabels',
                'revenueDayData'
            )
        );
    }

    public function storePerformance(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $startDate = $request->start_date ?? now()->subDays(30)->toDateString();

        $endDate = $request->end_date ?? now()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | ORDER ITEMS AGGREGATION
        |--------------------------------------------------------------------------
        | Prevent duplicate store rows by aggregating order_items per order first.
        |--------------------------------------------------------------------------
        */

        $orderItemSummary = DB::table('sales.order_items')
            ->selectRaw('
                order_id,
                SUM(quantity) as total_items_sold,
                SUM(
                    quantity *
                    list_price *
                    (1 - discount / 100)
                ) as total_revenue
            ')
            ->groupBy('order_id');

        $stores = DB::table('sales.stores as s')
            ->leftJoin('sales.orders as o', function ($join) use ($startDate, $endDate) {
                $join->on('s.store_id', '=', 'o.store_id')
                    ->whereDate('o.order_date', '>=', $startDate)
                    ->whereDate('o.order_date', '<=', $endDate);
            })
            ->leftJoinSub(
                $orderItemSummary,
                'ois',
                function ($join) {
                    $join->on('o.order_id', '=', 'ois.order_id');
                }
            )
            ->selectRaw('
                s.store_id,
                s.store_name,
                COUNT(DISTINCT o.order_id) as total_orders,
                COALESCE(SUM(ois.total_revenue), 0) as total_revenue,
                COALESCE(SUM(ois.total_items_sold), 0) as total_items_sold
            ')
            ->groupBy(
                's.store_id',
                's.store_name'
            )
            ->orderByDesc('total_revenue')
            ->get()
            ->unique('store_id')
            ->values();

        $topStore = $stores->first();

        $totalRevenue = $stores->sum('total_revenue');

        $totalOrders = $stores->sum('total_orders');

        $totalItemsSold = $stores->sum('total_items_sold');

        $storeLabels = $stores
            ->pluck('store_name')
            ->toArray();

        $revenueData = $stores
            ->pluck('total_revenue')
            ->toArray();

        $orderData = $stores
            ->pluck('total_orders')
            ->toArray();

        return view(
            'analytics.store-performance',
            compact(
                'startDate',
                'endDate',
                'stores',
                'topStore',
                'totalRevenue',
                'totalOrders',
                'totalItemsSold',
                'storeLabels',
                'revenueData',
                'orderData'
            )
        );
    }

    public function inventoryForecast(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $targetDays = 14;

        $forecasts = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->leftJoin('sales.order_items as oi', 's.product_id', '=', 'oi.product_id')
            ->leftJoin('sales.orders as o', function ($join) {
                $join->on('oi.order_id', '=', 'o.order_id')
                    ->on('s.store_id', '=', 'o.store_id');
            })
            ->selectRaw('
                s.store_id,
                s.product_id,
                st.store_name,
                p.product_name,
                s.quantity as current_stock,

                COALESCE(
                    SUM(
                        CASE
                            WHEN o.order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                            THEN oi.quantity
                            ELSE 0
                        END
                    ),
                    0
                ) as sold_last_30_days
            ')
            ->groupBy(
                's.store_id',
                's.product_id',
                'st.store_name',
                'p.product_name',
                's.quantity'
            )
            ->get()
            ->map(function ($item) use ($targetDays) {

                $avgDailySales = round($item->sold_last_30_days / 30, 2);

                if ($avgDailySales > 0) {
                    $daysLeft = round($item->current_stock / $avgDailySales, 1);
                } else {
                    $daysLeft = null;
                }

                $suggestedRestock = max(
                    0,
                    ceil(($avgDailySales * $targetDays) - $item->current_stock)
                );

                if ($daysLeft !== null && $daysLeft <= 3) {
                    $status = 'Critical';
                } elseif ($daysLeft !== null && $daysLeft <= 7) {
                    $status = 'Warning';
                } elseif ($avgDailySales == 0) {
                    $status = 'No Sales';
                } else {
                    $status = 'Safe';
                }

                $item->avg_daily_sales = $avgDailySales;
                $item->days_left = $daysLeft;
                $item->suggested_restock = $suggestedRestock;
                $item->forecast_status = $status;

                return $item;
            })
            ->sortBy(function ($item) {
                if ($item->forecast_status == 'Critical') {
                    return 1;
                }

                if ($item->forecast_status == 'Warning') {
                    return 2;
                }

                if ($item->forecast_status == 'Safe') {
                    return 3;
                }

                return 4;
            })
            ->values();

        $criticalCount = $forecasts
            ->where('forecast_status', 'Critical')
            ->count();

        $warningCount = $forecasts
            ->where('forecast_status', 'Warning')
            ->count();

        $safeCount = $forecasts
            ->where('forecast_status', 'Safe')
            ->count();

        $noSalesCount = $forecasts
            ->where('forecast_status', 'No Sales')
            ->count();

        return view(
            'analytics.inventory-forecast',
            compact(
                'forecasts',
                'criticalCount',
                'warningCount',
                'safeCount',
                'noSalesCount',
                'targetDays'
            )
        );
    }
}