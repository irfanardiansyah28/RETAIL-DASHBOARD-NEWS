<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CustomerSegmentationService
{
    public function run()
    {
        $customers = DB::table(
            'sales.customers'
        )->get();

        foreach($customers as $customer){

            $totalSpent = DB::table(
                'sales.orders as o'
            )

            ->join(
                'sales.order_items as oi',
                'o.order_id',
                '=',
                'oi.order_id'
            )

            ->where(
                'o.customer_id',
                $customer->customer_id
            )

            ->sum(
                DB::raw(
                    '
                    oi.quantity*
                    oi.list_price*
                    (1-oi.discount/100)
                    '
                )
            );

            $orderCount = DB::table(
                'sales.orders'
            )

            ->where(
                'customer_id',
                $customer->customer_id
            )

            ->count();

            $lastOrder = DB::table(
                'sales.orders'
            )

            ->where(
                'customer_id',
                $customer->customer_id
            )

            ->latest(
                'order_date'
            )

            ->first();

            $hasRisk = DB::table(
                'risk_flags'
            )

            ->where(
                'module',
                'Customer'
            )

            ->where(
                'reference_id',
                $customer->customer_id
            )

            ->where(
                'status',
                'Open'
            )

            ->exists();

            $segment='New';

            if($hasRisk){

                $segment='High Risk';

            }

            elseif($lastOrder){

                if(
                    now()->diffInDays(
                        $lastOrder->order_date
                    ) > 90
                ){

                    $segment='Dormant';

                }

                elseif(
                    $totalSpent >=
                    10000000
                ){

                    $segment='VIP';

                }

                elseif(
                    $orderCount >=3
                ){

                    $segment='Regular';

                }

            }

            DB::table(
                'sales.customers'
            )

            ->where(
                'customer_id',
                $customer->customer_id
            )

            ->update([

                'segment'=>$segment

            ]);

        }

        return true;
    }
}