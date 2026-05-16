<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRiskService
{

    public function calculate()
    {

        $customers=
        Customer::all();

        foreach(
            $customers as $customer
        ){

            $score=0;

            if(
                empty($customer->email)
            ){
                $score+=20;
            }

            if(
                empty($customer->phone)
            ){
                $score+=20;
            }

            if(
                empty($customer->street)
            ){
                $score+=20;
            }

            $cancelled=
            DB::table('orders')

            ->where(
                'customer_id',
                $customer->customer_id
            )

            ->where(
                'status',
                'Cancelled'
            )

            ->count();

            if(
                $cancelled>=3
            ){
                $score+=25;
            }

            if(
                $customer->segment==
                'Dormant'
            ){

                $score+=15;

            }

            $customer->update([

                'risk_score'=>

                min(
                    $score,
                    100
                )

            ]);

        }

    }

}