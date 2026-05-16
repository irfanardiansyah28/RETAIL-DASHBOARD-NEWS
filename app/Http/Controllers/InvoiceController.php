<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function generate($id)
    {

        $order = DB::table('sales.orders as o')

            ->join(
                'sales.customers as c',
                'o.customer_id',
                '=',
                'c.customer_id'
            )

            ->selectRaw('
                o.order_id,
                o.order_date,

                CONCAT(
                    c.first_name,
                    " ",
                    c.last_name
                ) as customer_name,

                c.email,
                c.phone
            ')

            ->where(
                'o.order_id',
                $id
            )

            ->first();

        $items = DB::table('sales.order_items as oi')

            ->join(
                'production.products as p',
                'oi.product_id',
                '=',
                'p.product_id'
            )

            ->selectRaw('
                p.product_name,

                oi.quantity,
                oi.list_price,

                (
                    oi.quantity *
                    oi.list_price
                ) as subtotal
            ')

            ->where(
                'oi.order_id',
                $id
            )

            ->get();

        $grandTotal = $items->sum('subtotal');

        $pdf = Pdf::loadView(
            'orders.invoice',
            compact(
                'order',
                'items',
                'grandTotal'
            )
        );

        return $pdf->download(
            'invoice-'.$order->order_id.'.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW INVOICE
    |--------------------------------------------------------------------------
    */

    public function preview($id)
    {

        $order = DB::table('sales.orders as o')

            ->join(
                'sales.customers as c',
                'o.customer_id',
                '=',
                'c.customer_id'
            )

            ->selectRaw('
                o.order_id,
                o.order_date,

                CONCAT(
                    c.first_name,
                    " ",
                    c.last_name
                ) as customer_name,

                c.email,
                c.phone
            ')

            ->where(
                'o.order_id',
                $id
            )

            ->first();

        $items = DB::table('sales.order_items as oi')

            ->join(
                'production.products as p',
                'oi.product_id',
                '=',
                'p.product_id'
            )

            ->selectRaw('
                p.product_name,

                oi.quantity,
                oi.list_price,

                (
                    oi.quantity *
                    oi.list_price
                ) as subtotal
            ')

            ->where(
                'oi.order_id',
                $id
            )

            ->get();

        $grandTotal = $items->sum('subtotal');

        return view(
            'orders.invoice-preview',
            compact(
                'order',
                'items',
                'grandTotal'
            )
        );
    }
}