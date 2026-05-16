<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SmartRecommendationController;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;

        $endDate = $request->end_date;

        /*
        |--------------------------------------------------------------------------
        | BASIC KPI
        |--------------------------------------------------------------------------
        */

        $totalProducts = DB::table('production.products')
            ->count();

        $totalCustomers = DB::table('sales.customers')
            ->count();

        $totalOrders = DB::table('sales.orders')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('order_date', '<=', $endDate);
            })
            ->count();

        $totalRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('o.order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('o.order_date', '<=', $endDate);
            })
            ->sum(
                DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
            );

        /*
        |--------------------------------------------------------------------------
        | MONTHLY ORDERS CHART
        |--------------------------------------------------------------------------
        */

        $monthlyOrders = DB::table('sales.orders')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('order_date', '<=', $endDate);
            })
            ->selectRaw('MONTH(order_date) as month, COUNT(*) as total')
            ->groupByRaw('MONTH(order_date)')
            ->orderByRaw('MONTH(order_date)')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MONTHLY REVENUE CHART
        |--------------------------------------------------------------------------
        */

        $monthlyRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('o.order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('o.order_date', '<=', $endDate);
            })
            ->selectRaw('
                MONTH(o.order_date) as month,
                SUM(oi.quantity * oi.list_price * (1 - oi.discount / 100)) as revenue
            ')
            ->groupByRaw('MONTH(o.order_date)')
            ->orderByRaw('MONTH(o.order_date)')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS EXISTING DASHBOARD
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('sales.order_items as oi')
            ->join('sales.orders as o', 'oi.order_id', '=', 'o.order_id')
            ->join('production.products as p', 'oi.product_id', '=', 'p.product_id')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('o.order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('o.order_date', '<=', $endDate);
            })
            ->selectRaw('
                p.product_name,
                SUM(oi.quantity) as total_sold
            ')
            ->groupBy('p.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | LOW STOCK
        |--------------------------------------------------------------------------
        */

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
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT ORDERS
        |--------------------------------------------------------------------------
        */

        $recentOrders = DB::table('sales.orders as o')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->leftJoin('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('o.order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('o.order_date', '<=', $endDate);
            })
            ->selectRaw('
                o.order_id,
                o.order_date,
                o.status,
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                SUM(oi.quantity * oi.list_price * (1 - oi.discount / 100)) as total
            ')
            ->groupBy(
                'o.order_id',
                'o.order_date',
                'o.status',
                'c.first_name',
                'c.last_name'
            )
            ->orderByDesc('o.order_id')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | BEST CUSTOMERS
        |--------------------------------------------------------------------------
        */

        $bestCustomers = DB::table('sales.orders as o')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('o.order_date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('o.order_date', '<=', $endDate);
            })
            ->selectRaw('
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                SUM(oi.quantity * oi.list_price * (1 - oi.discount / 100)) as total_spent
            ')
            ->groupBy(
                'c.first_name',
                'c.last_name'
            )
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PENDING ORDERS
        |--------------------------------------------------------------------------
        */

        $pendingOrders = DB::table('sales.orders')
            ->where('status', 'Pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | NEW KPI ANALYTICS
        |--------------------------------------------------------------------------
        */

        $todayRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->whereDate('o.order_date', now()->toDateString())
            ->where('o.status', 'Completed')
            ->sum(
                DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
            );

        $weeklyRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->whereBetween('o.order_date', [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ])
            ->where('o.status', 'Completed')
            ->sum(
                DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
            );

        $currentMonthRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->whereMonth('o.order_date', now()->month)
            ->whereYear('o.order_date', now()->year)
            ->where('o.status', 'Completed')
            ->sum(
                DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
            );


            /*
|--------------------------------------------------------------------------
| KPI TREND COMPARISON
|--------------------------------------------------------------------------
*/

$yesterdayRevenue = DB::table('sales.orders as o')
->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
->whereDate('o.order_date', now()->subDay()->toDateString())
->where('o.status', 'Completed')
->sum(
    DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
);

$lastWeekRevenue = DB::table('sales.orders as o')
->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
->whereBetween('o.order_date', [
    now()->subWeek()->startOfWeek()->toDateString(),
    now()->subWeek()->endOfWeek()->toDateString(),
])
->where('o.status', 'Completed')
->sum(
    DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
);

$lastMonthRevenue = DB::table('sales.orders as o')
->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
->whereMonth('o.order_date', now()->subMonth()->month)
->whereYear('o.order_date', now()->subMonth()->year)
->where('o.status', 'Completed')
->sum(
    DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
);

$todayRevenueTrend = calculateTrend(
$todayRevenue,
$yesterdayRevenue
);

$weeklyRevenueTrend = calculateTrend(
$weeklyRevenue,
$lastWeekRevenue
);

$monthlyRevenueTrend = calculateTrend(
$currentMonthRevenue,
$lastMonthRevenue
);

        $completedOrders = DB::table('sales.orders')
            ->where('status', 'Completed')
            ->count();

        $topSellingProducts = DB::table('sales.order_items as oi')
            ->join('production.products as p', 'oi.product_id', '=', 'p.product_id')
            ->select(
                'p.product_name',
                DB::raw('SUM(oi.quantity) as total_sold'),
                DB::raw('SUM(oi.quantity * oi.list_price * (1 - oi.discount / 100)) as total_revenue')
            )
            ->groupBy('p.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $orderStatusSummary = DB::table('sales.orders')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->get();

        $dailyRevenueChart = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->select(
                DB::raw('DATE(o.order_date) as date'),
                DB::raw('SUM(oi.quantity * oi.list_price * (1 - oi.discount / 100)) as revenue')
            )
            ->where('o.status', 'Completed')
            ->whereDate('o.order_date', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(o.order_date)')
            ->orderByRaw('DATE(o.order_date)')
            ->get();

        $revenueChartLabels = $dailyRevenueChart
            ->pluck('date')
            ->toArray();

        $revenueChartData = $dailyRevenueChart
            ->pluck('revenue')
            ->toArray();

        $orderStatusLabels = $orderStatusSummary
            ->pluck('status')
            ->toArray();

        $orderStatusData = $orderStatusSummary
            ->pluck('total')
            ->toArray();


            /*
|--------------------------------------------------------------------------
| RISK SUMMARY
|--------------------------------------------------------------------------
*/

$openRiskFlags = DB::table('risk_flags')
->where('status', 'Open')
->count();

$highRiskFlags = DB::table('risk_flags')
->where('status', 'Open')
->where('severity', 'High')
->count();

$mediumRiskFlags = DB::table('risk_flags')
->where('status', 'Open')
->where('severity', 'Medium')
->count();

$lowRiskFlags = DB::table('risk_flags')
->where('status', 'Open')
->where('severity', 'Low')
->count();


/*
|--------------------------------------------------------------------------
| SMART RECOMMENDATIONS
|--------------------------------------------------------------------------
*/

$smartInsights =
SmartRecommendationController::getInsights();


/*
|--------------------------------------------------------------------------
| EXECUTIVE SUMMARY
|--------------------------------------------------------------------------
*/

$healthScore = 100;

$executiveInsights = [];

/*
|--------------------------------------------------------------------------
| Revenue trend
|--------------------------------------------------------------------------
*/

if($monthlyRevenueTrend < 0){

    $healthScore -= 10;

    $executiveInsights[] = [
        'icon'=>'🔴',
        'text'=>'Revenue trend decreased compared with previous period'
    ];

}else{

    $executiveInsights[] = [
        'icon'=>'🟢',
        'text'=>'Revenue trend remains healthy'
    ];

}


/*
|--------------------------------------------------------------------------
| Open Risk Flags
|--------------------------------------------------------------------------
*/

if($openRiskFlags >= 10){

    $healthScore -= 20;

    $executiveInsights[] = [
        'icon'=>'🔴',
        'text'=>
        $openRiskFlags.
        ' active risk flags need review'
    ];

}
elseif($openRiskFlags >=5){

    $healthScore -=10;

    $executiveInsights[]=[
        'icon'=>'🟡',
        'text'=>'Moderate operational risk detected'
    ];

}
else{

    $executiveInsights[]=[
        'icon'=>'🟢',
        'text'=>'Fraud risk under control'
    ];

}


/*
|--------------------------------------------------------------------------
| Low stock
|--------------------------------------------------------------------------
*/

$lowStockCount = collect(
    $lowStocks
)->count();

if($lowStockCount>=5){

    $healthScore -=15;

    $executiveInsights[]=[
        'icon'=>'🟡',
        'text'=>
        $lowStockCount.
        ' products need urgent restock'
    ];

}
else{

    $executiveInsights[]=[
        'icon'=>'🟢',
        'text'=>'Inventory level healthy'
    ];

}


/*
|--------------------------------------------------------------------------
| High risk customers
|--------------------------------------------------------------------------
*/

$highRiskCustomerCount=

DB::table(
'sales.customers'
)

->where(
'risk_level',
'High'
)

->count();

if(
$highRiskCustomerCount>=5
){

$healthScore-=15;

$executiveInsights[]=[
'icon'=>'🔴',
'text'=>
$highRiskCustomerCount.
' high risk customers detected'
];

}
else{

$executiveInsights[]=[
'icon'=>'🟢',
'text'=>'Customer quality stable'
];

}


/*
|--------------------------------------------------------------------------
| Store performance
|--------------------------------------------------------------------------
*/

$storesBelowTarget=

DB::table(
'sales.stores as s'
)

->leftJoin(
'sales.orders as o',
's.store_id',
'=',
'o.store_id'
)

->selectRaw('
s.store_name,
COUNT(o.order_id)
as total_orders
')

->groupBy(
's.store_name'
)

->having(
'total_orders',
'<',
5
)

->count();

if(
$storesBelowTarget>0
){

$healthScore-=10;

$executiveInsights[]=[

'icon'=>'🟡',

'text'=>
$storesBelowTarget.
' store(s) underperforming'

];

}
else{

$executiveInsights[]=[

'icon'=>'🟢',

'text'=>'Store performance healthy'

];

}


$healthScore=max(
0,
$healthScore
);


if(
$healthScore>=80
){
$businessStatus='Excellent';
}
elseif(
$healthScore>=60
){
$businessStatus='Good';
}
elseif(
$healthScore>=40
){
$businessStatus='Warning';
}
else{
$businessStatus='Critical';
}

        /*
|--------------------------------------------------------------------------
| RETURN VIEW
|--------------------------------------------------------------------------
*/

return view(
    'dashboard',
    compact(
        'totalProducts',
        'totalCustomers',
        'totalOrders',
        'totalRevenue',
        'monthlyOrders',
        'monthlyRevenue',
        'topProducts',
        'lowStocks',
        'lowStockLimit',
        'recentOrders',
        'bestCustomers',
        'pendingOrders',
        'todayRevenue',
        'weeklyRevenue',
        'currentMonthRevenue',
        'todayRevenueTrend',
        'weeklyRevenueTrend',
        'monthlyRevenueTrend',
        'completedOrders',
        'topSellingProducts',
        'orderStatusLabels',
        'orderStatusData',
        'revenueChartLabels',
        'revenueChartData',
        'openRiskFlags',
        'highRiskFlags',
        'mediumRiskFlags',
        'lowRiskFlags',
        'healthScore',
        'businessStatus',
        'executiveInsights',
        'startDate',
        'endDate',

        // tambah ini
        'smartInsights'
    )
);
    }
}