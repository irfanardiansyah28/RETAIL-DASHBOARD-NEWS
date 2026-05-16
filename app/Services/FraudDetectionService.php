<?php

namespace App\Services;

use App\Models\RiskFlag;
use App\Models\RulePerformance;
use Illuminate\Support\Facades\DB;


class FraudDetectionService
{
    public function scan()
    {
        $created = 0;

        $created += $this->detectHighValueOrders();

        $created += $this->detectCustomerHighFrequencyOrders();

        $created += $this->detectCustomerCancelledOrders();

        $created += $this->detectUserFrequentCancelOrders();

        $created += $this->detectLargeStockDecrease();

        return $created;
    }

    private function createUniqueRiskFlag(
        $riskType,
        $severity,
        $module,
        $referenceId,
        $title,
        $description
    ){
    
        $exists=
        RiskFlag::where(
            'risk_type',
            $riskType
        )
    
        ->where(
            'module',
            $module
        )
    
        ->where(
            'reference_id',
            $referenceId
        )
    
        ->where(
            'status',
            'Open'
        )
    
        ->exists();
    
        if($exists){
    
            return 0;
    
        }
    
        RiskFlag::create([
    
            'risk_type'=>$riskType,
    
            'severity'=>$severity,
    
            'module'=>$module,
    
            'reference_id'=>$referenceId,
    
            'title'=>$title,
    
            'description'=>$description,
    
            'user_id'=>null,
    
            'user_name'=>'Fraud Engine',
    
            'status'=>'Open'
    
        ]);
    
    
        $rule=
        RulePerformance::firstOrCreate(
    
            [
                'rule_name'=>$title
            ],
    
            [
                'trigger_count'=>0,
                'high_risk_count'=>0,
                'medium_risk_count'=>0,
                'low_risk_count'=>0
            ]
        );
    
    
        $rule->increment(
            'trigger_count'
        );
    
        if(
            strtolower($severity)
            =='high'
        ){
            $rule->increment(
                'high_risk_count'
            );
        }
    
        if(
            strtolower($severity)
            =='medium'
        ){
            $rule->increment(
                'medium_risk_count'
            );
        }
    
        if(
            strtolower($severity)
            =='low'
        ){
            $rule->increment(
                'low_risk_count'
            );
        }
    
        return 1;
    }

    private function detectHighValueOrders()
    {
        $created = 0;

        $threshold = 10000000;

        $orders = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                o.order_id,
                o.order_date,
                o.status,
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                SUM(
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as total_amount
            ')
            ->whereDate('o.order_date', '>=', now()->subDays(30)->toDateString())
            ->groupBy(
                'o.order_id',
                'o.order_date',
                'o.status',
                'c.first_name',
                'c.last_name'
            )
            ->having('total_amount', '>=', $threshold)
            ->get();

        foreach ($orders as $order) {
            $created += $this->createUniqueRiskFlag(
                'High Value Order',
                'High',
                'Order',
                $order->order_id,
                'High value order detected',
                'Order #'
                    .$order->order_id
                    .' by '
                    .$order->customer_name
                    .' has amount '
                    .number_format($order->total_amount, 0, ',', '.')
            );
        }

        return $created;
    }

    private function detectCustomerHighFrequencyOrders()
    {
        $created = 0;

        $customers = DB::table('sales.orders as o')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                o.customer_id,
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                COUNT(*) as total_orders
            ')
            ->whereDate('o.order_date', now()->toDateString())
            ->groupBy(
                'o.customer_id',
                'c.first_name',
                'c.last_name'
            )
            ->having('total_orders', '>=', 5)
            ->get();

        foreach ($customers as $customer) {
            $created += $this->createUniqueRiskFlag(
                'High Frequency Orders',
                'Medium',
                'Customer',
                $customer->customer_id,
                'Customer created many orders today',
                $customer->customer_name
                    .' created '
                    .$customer->total_orders
                    .' orders today.'
            );
        }

        return $created;
    }

    private function detectCustomerCancelledOrders()
    {
        $created = 0;

        $customers = DB::table('sales.orders as o')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                o.customer_id,
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                COUNT(*) as cancelled_orders
            ')
            ->where('o.status', 'Cancelled')
            ->whereDate('o.order_date', '>=', now()->subDays(30)->toDateString())
            ->groupBy(
                'o.customer_id',
                'c.first_name',
                'c.last_name'
            )
            ->having('cancelled_orders', '>=', 3)
            ->get();

        foreach ($customers as $customer) {
            $created += $this->createUniqueRiskFlag(
                'Repeated Cancelled Orders',
                'Medium',
                'Customer',
                $customer->customer_id,
                'Customer has repeated cancelled orders',
                $customer->customer_name
                    .' has '
                    .$customer->cancelled_orders
                    .' cancelled orders in the last 30 days.'
            );
        }

        return $created;
    }

    private function detectUserFrequentCancelOrders()
    {
        $created = 0;

        $users = DB::table('activity_logs')
            ->selectRaw('
                user_name,
                COUNT(*) as total_cancel
            ')
            ->where('action', 'like', '%Cancel Order%')
            ->whereDate('created_at', now()->toDateString())
            ->groupBy('user_name')
            ->having('total_cancel', '>=', 5)
            ->get();

        foreach ($users as $user) {
            $created += $this->createUniqueRiskFlag(
                'Frequent Order Cancellation By User',
                'High',
                'Activity',
                null,
                'User cancelled many orders today',
                ($user->user_name ?? 'Unknown user')
                    .' cancelled '
                    .$user->total_cancel
                    .' orders today.'
            );
        }

        return $created;
    }

    private function detectLargeStockDecrease()
    {
        $created = 0;

        $movements = DB::table('stock_movements')
            ->where('difference', '<=', -50)
            ->whereDate('created_at', '>=', now()->subDays(7)->toDateString())
            ->get();

        foreach ($movements as $movement) {
            $created += $this->createUniqueRiskFlag(
                'Large Stock Decrease',
                'High',
                'Stock',
                $movement->id,
                'Large stock decrease detected',
                $movement->product_name
                    .' at '
                    .$movement->store_name
                    .' decreased by '
                    .abs($movement->difference)
                    .' units.'
            );
        }

        return $created;
    }
}