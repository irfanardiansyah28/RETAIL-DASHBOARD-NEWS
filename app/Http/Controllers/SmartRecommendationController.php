<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SmartRecommendationController extends Controller
{
    public static function getInsights()
    {
        $insights = [];

        /*
        |--------------------------------------------------------------------------
        | LOW STOCK FORECAST
        |--------------------------------------------------------------------------
        */

        $forecast = DB::table('production.stocks as s')
            ->join(
                'production.products as p',
                'p.product_id',
                '=',
                's.product_id'
            )
            ->join(
                'sales.stores as st',
                'st.store_id',
                '=',
                's.store_id'
            )
            ->select(
                'p.product_name',
                'st.store_name',
                's.quantity',
                DB::raw('ROUND(s.quantity / 5) as days_left')
            )
            ->where(
                's.quantity',
                '<',
                30
            )
            ->orderBy(
                's.quantity',
                'asc'
            )
            ->limit(3)
            ->get();

        foreach ($forecast as $item) {

            $insights[] = [
                'icon' => '⚠',
                'type' => 'warning',
                'text' => $item->product_name
                    .' at '
                    .$item->store_name
                    .' predicted out of stock in '
                    .max($item->days_left, 1)
                    .' day(s)',
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | TOP STORE PERFORMANCE
        |--------------------------------------------------------------------------
        */

        $topStore = DB::table('sales.orders as o')
            ->join(
                'sales.stores as s',
                's.store_id',
                '=',
                'o.store_id'
            )
            ->join(
                'sales.order_items as oi',
                'o.order_id',
                '=',
                'oi.order_id'
            )
            ->selectRaw('
                s.store_name,
                SUM(
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as total
            ')
            ->whereDate(
                'o.order_date',
                '>=',
                now()->subDays(30)->toDateString()
            )
            ->groupBy(
                's.store_name'
            )
            ->orderByDesc(
                'total'
            )
            ->first();

        if ($topStore) {

            $insights[] = [
                'icon' => '📈',
                'type' => 'success',
                'text' => $topStore->store_name
                    .' currently leads store revenue in the last 30 days',
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER CANCEL BEHAVIOR
        |--------------------------------------------------------------------------
        */

        $riskCustomer = DB::table('sales.orders as o')
            ->join(
                'sales.customers as c',
                'c.customer_id',
                '=',
                'o.customer_id'
            )
            ->selectRaw('
                o.customer_id,
                c.first_name,
                c.last_name,
                COUNT(*) as total
            ')
            ->where(
                'o.status',
                'Cancelled'
            )
            ->whereDate(
                'o.order_date',
                '>=',
                now()->subDays(30)->toDateString()
            )
            ->groupBy(
                'o.customer_id',
                'c.first_name',
                'c.last_name'
            )
            ->havingRaw(
                'COUNT(*) >= 3'
            )
            ->orderByDesc(
                'total'
            )
            ->first();

        if ($riskCustomer) {

            $insights[] = [
                'icon' => '🚩',
                'type' => 'danger',
                'text' => $riskCustomer->first_name
                    .' '
                    .$riskCustomer->last_name
                    .' has '
                    .$riskCustomer->total
                    .' cancelled orders in the last 30 days',
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | OVERSTOCK DETECTION
        |--------------------------------------------------------------------------
        */

        $overStock = DB::table('production.stocks as s')
            ->join(
                'production.products as p',
                'p.product_id',
                '=',
                's.product_id'
            )
            ->join(
                'sales.stores as st',
                'st.store_id',
                '=',
                's.store_id'
            )
            ->select(
                'p.product_name',
                'st.store_name',
                's.quantity'
            )
            ->where(
                's.quantity',
                '>',
                500
            )
            ->orderByDesc(
                's.quantity'
            )
            ->first();

        if ($overStock) {

            $insights[] = [
                'icon' => '📦',
                'type' => 'primary',
                'text' => $overStock->product_name
                    .' at '
                    .$overStock->store_name
                    .' may be overstocked with '
                    .$overStock->quantity
                    .' units',
            ];

        }

        /*
        |--------------------------------------------------------------------------
        | OPEN HIGH RISK FLAGS
        |--------------------------------------------------------------------------
        */

        $highRiskCount = DB::table('risk_flags')
            ->where(
                'status',
                'Open'
            )
            ->where(
                'severity',
                'High'
            )
            ->count();

        if ($highRiskCount > 0) {

            $insights[] = [
                'icon' => '🛡️',
                'type' => 'danger',
                'text' => $highRiskCount
                    .' open high risk flag(s) need review',
            ];

        }

        /*
|--------------------------------------------------------------------------
| DYNAMIC FRAUD PATTERN INSIGHT
|--------------------------------------------------------------------------
*/

$dynamicPatternCount = DB::table('risk_flags')
->where('status', 'Open')
->whereIn('risk_type', [
    'High Risk Customer With High Value Order',
    'Repeated Cancelled Orders Pattern',
    'Abnormal Cancel Spike',
    'Repeated High Value Orders',
    'Similar Phone Pattern',
    'Incomplete Profile With Active Orders',
])
->count();

if ($dynamicPatternCount > 0) {

$insights[] = [
    'icon' => '🧩',
    'type' => 'danger',
    'text' => $dynamicPatternCount
        .' dynamic fraud pattern(s) detected and need review',
];

}

        return $insights;
    }
}