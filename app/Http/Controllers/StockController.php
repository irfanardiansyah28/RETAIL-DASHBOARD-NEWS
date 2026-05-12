<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class StockController extends Controller
{

    public function index(Request $request)
    {

        $search = $request->search;

        $stocks = DB::table('production.stocks as s')

            ->join(
                'production.products as p',
                's.product_id',
                '=',
                'p.product_id'
            )

            ->join(
                'sales.stores as st',
                's.store_id',
                '=',
                'st.store_id'
            )

            ->select(
                's.store_id',
                's.product_id',
                'p.product_name',
                'st.store_name',
                's.quantity'
            )

            ->when($search, function($query) use ($search) {

                $query->where(
                    'p.product_name',
                    'like',
                    "%{$search}%"
                );

            })

            ->paginate(10);

        return view(
            'stocks.index',
            compact(
                'stocks',
                'search'
            )
        );
    }

    // EDIT STOCK

    public function edit($store_id, $product_id)
    {

        $stock = DB::table('production.stocks as s')

            ->join(
                'production.products as p',
                's.product_id',
                '=',
                'p.product_id'
            )

            ->join(
                'sales.stores as st',
                's.store_id',
                '=',
                'st.store_id'
            )

            ->select(
                's.*',
                'p.product_name',
                'st.store_name'
            )

            ->where(
                's.store_id',
                $store_id
            )

            ->where(
                's.product_id',
                $product_id
            )

            ->first();

        return view(
            'stocks.edit',
            compact('stock')
        );
    }

    // UPDATE STOCK

    public function update(Request $request)
    {

        DB::table('production.stocks')

            ->where(
                'store_id',
                $request->store_id
            )

            ->where(
                'product_id',
                $request->product_id
            )

            ->update([

                'quantity' =>
                    $request->quantity

            ]);

        return redirect('/stocks')

            ->with(
                'success',
                'Stock updated successfully'
            );
    }
}