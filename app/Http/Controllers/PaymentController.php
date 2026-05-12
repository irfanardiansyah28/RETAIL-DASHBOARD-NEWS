<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{

    public function pay($id)
    {

        $total = DB::table(
            'sales.order_items'
        )

        ->where('order_id', $id)

        ->selectRaw('
            SUM(
                quantity *
                list_price *
                (1-discount)
            ) as total
        ')

        ->first();

        DB::table('sales.payments')

            ->insert([

                'order_id' => $id,

                'payment_date' => now(),

                'amount' => $total->total,

                'payment_method' => 'Transfer',

                'status' => 'Paid'

            ]);

        DB::table('sales.orders')

            ->where('order_id', $id)

            ->update([

                'status' => 'Paid'

            ]);

        return back();
    }
}