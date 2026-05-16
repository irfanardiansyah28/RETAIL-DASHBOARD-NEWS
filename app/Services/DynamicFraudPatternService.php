<?php

namespace App\Services;

use App\Models\RiskFlag;
use App\Models\RulePerformance;
use Illuminate\Support\Facades\DB;

class DynamicFraudPatternService
{
    public function scan()
    {
        $created=0;

        $created += $this->detectHighRiskCustomerWithHighValueOrder();

        $created += $this->detectRepeatedCancelledOrders();

        $created += $this->detectCancelSpikeToday();

        $created += $this->detectRepeatedHighValueOrders();

        $created += $this->detectSimilarPhonePattern();

        $created += $this->detectIncompleteProfileWithOrders();

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
        RiskFlag::where('risk_type',$riskType)
        ->where('module',$module)
        ->where('reference_id',$referenceId)
        ->where('status','Open')
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
            'user_id'=>auth()->id(),
            'user_name'=>auth()->check()
                ? auth()->user()->name
                : 'Dynamic Fraud Engine',
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

        $rule->increment('trigger_count');

        if(strtolower($severity)=='high'){
            $rule->increment('high_risk_count');
        }

        if(strtolower($severity)=='medium'){
            $rule->increment('medium_risk_count');
        }

        if(strtolower($severity)=='low'){
            $rule->increment('low_risk_count');
        }

        return 1;
    }


    private function detectHighRiskCustomerWithHighValueOrder()
    {
        $created=0;

        $customers=DB::table('sales.customers as c')
        ->join(
            'sales.orders as o',
            'c.customer_id',
            '=',
            'o.customer_id'
        )
        ->join(
            'sales.order_items as oi',
            'o.order_id',
            '=',
            'oi.order_id'
        )

        ->selectRaw('
            c.customer_id,
            c.first_name,
            c.last_name,
            c.risk_score,
            o.order_id,

            SUM(
                oi.quantity*
                oi.list_price*
                (1-oi.discount/100)
            ) as order_amount
        ')

        ->where(
            'c.risk_level',
            'High'
        )

        ->groupBy(
            'c.customer_id',
            'c.first_name',
            'c.last_name',
            'c.risk_score',
            'o.order_id'
        )

        ->having(
            'order_amount',
            '>=',
            10000000
        )

        ->get();

        foreach($customers as $customer){

            $created +=
            $this->createUniqueRiskFlag(

                'High Risk Customer With High Value Order',

                'High',

                'Customer',

                $customer->customer_id,

                'High risk customer made high value order',

                $customer->first_name.' '.$customer->last_name.
                ' amount '.number_format(
                    $customer->order_amount
                )

            );
        }

        return $created;
    }


    private function detectRepeatedCancelledOrders()
    {
        $created=0;

        $customers=
        DB::table('sales.orders')

        ->selectRaw('
            customer_id,
            COUNT(*) total
        ')

        ->where(
            'status',
            'Cancelled'
        )

        ->whereDate(
            'order_date',
            '>=',
            now()->subDays(30)
        )

        ->groupBy(
            'customer_id'
        )

        ->having(
            'total',
            '>=',
            3
        )

        ->get();

        foreach($customers as $c){

            $created +=
            $this->createUniqueRiskFlag(

                'Repeated Cancelled Orders',

                'Medium',

                'Customer',

                $c->customer_id,

                'Repeated cancelled orders detected',

                'Customer has '.$c->total.' cancelled orders'

            );

        }

        return $created;
    }


    private function detectCancelSpikeToday()
    {
        return 0;
    }

    private function detectRepeatedHighValueOrders()
    {
        return 0;
    }


    private function detectSimilarPhonePattern()
    {
        return 0;
    }


    private function detectIncompleteProfileWithOrders()
    {
        return 0;
    }

}