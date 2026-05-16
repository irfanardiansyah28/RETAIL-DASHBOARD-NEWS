<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CustomerRiskScoreService
{
    public function calculate()
    {
        $customers = DB::table('sales.customers')
            ->get();

        foreach ($customers as $customer) {

            $score = 0;

            /*
            |--------------------------------------------------------------------------
            | PROFILE COMPLETENESS RISK
            |--------------------------------------------------------------------------
            */

            if (empty($customer->email)) {
                $score += 10;
            }

            if (empty($customer->phone)) {
                $score += 10;
            }

            if (empty($customer->street)) {
                $score += 10;
            }

            if (empty($customer->city)) {
                $score += 5;
            }

            if (empty($customer->state)) {
                $score += 5;
            }

            if (empty($customer->zip_code)) {
                $score += 5;
            }

            /*
            |--------------------------------------------------------------------------
            | CANCELLED ORDERS
            |--------------------------------------------------------------------------
            */

            $cancelledOrders = DB::table('sales.orders')
                ->where('customer_id', $customer->customer_id)
                ->where('status', 'Cancelled')
                ->whereDate(
                    'order_date',
                    '>=',
                    now()->subDays(90)->toDateString()
                )
                ->count();

            if ($cancelledOrders >= 5) {
                $score += 30;
            } elseif ($cancelledOrders >= 3) {
                $score += 20;
            } elseif ($cancelledOrders >= 1) {
                $score += 10;
            }

            /*
            |--------------------------------------------------------------------------
            | OPEN RISK FLAGS
            |--------------------------------------------------------------------------
            */

            $openRiskFlags = DB::table('risk_flags')
                ->where('module', 'Customer')
                ->where('reference_id', $customer->customer_id)
                ->where('status', 'Open')
                ->count();

            if ($openRiskFlags >= 3) {
                $score += 35;
            } elseif ($openRiskFlags >= 1) {
                $score += 25;
            }

            /*
            |--------------------------------------------------------------------------
            | HIGH VALUE ORDER
            |--------------------------------------------------------------------------
            */

            $highValueOrders = DB::table('sales.orders as o')
                ->join(
                    'sales.order_items as oi',
                    'o.order_id',
                    '=',
                    'oi.order_id'
                )
                ->where('o.customer_id', $customer->customer_id)
                ->selectRaw('
                    o.order_id,
                    SUM(
                        oi.quantity *
                        oi.list_price *
                        (1 - oi.discount / 100)
                    ) as total_amount
                ')
                ->groupBy('o.order_id')
                ->having('total_amount', '>=', 10000000)
                ->count();

            if ($highValueOrders >= 1) {
                $score += 15;
            }

            /*
            |--------------------------------------------------------------------------
            | SEGMENT RISK
            |--------------------------------------------------------------------------
            */

            if (($customer->segment ?? 'New') == 'Dormant') {
                $score += 15;
            }

            if (($customer->segment ?? 'New') == 'High Risk') {
                $score += 30;
            }

            /*
            |--------------------------------------------------------------------------
            | FINAL SCORE
            |--------------------------------------------------------------------------
            */

            $score = min($score, 100);

            if ($score >= 70) {
                $level = 'High';
            } elseif ($score >= 40) {
                $level = 'Medium';
            } else {
                $level = 'Low';
            }

            DB::table('sales.customers')
                ->where('customer_id', $customer->customer_id)
                ->update([
                    'risk_score' => $score,
                    'risk_level' => $level,
                ]);
        }

        return true;
    }
}