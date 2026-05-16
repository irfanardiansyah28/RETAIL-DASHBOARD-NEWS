<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->search;

        if (!$keyword || strlen($keyword) < 2) {
            return response()->json([
                'products' => [],
                'orders' => [],
                'customers' => [],
                'stocks' => [],
                'users' => [],
            ]);
        }

        $products = DB::table('production.products as p')
            ->join('production.brands as b', 'p.brand_id', '=', 'b.brand_id')
            ->join('production.categories as c', 'p.category_id', '=', 'c.category_id')
            ->select(
                'p.product_id',
                'p.product_name',
                'b.brand_name',
                'c.category_name'
            )
            ->where('p.product_name', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        $orders = DB::table('sales.orders as o')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                o.order_id,
                o.order_date,
                o.status,
                CONCAT(c.first_name, " ", c.last_name) as customer_name
            ')
            ->where('o.order_id', 'like', "%{$keyword}%")
            ->orWhere('c.first_name', 'like', "%{$keyword}%")
            ->orWhere('c.last_name', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        $customers = DB::table('sales.customers')
            ->select(
                'customer_id',
                'first_name',
                'last_name',
                'email',
                'phone'
            )
            ->where('first_name', 'like', "%{$keyword}%")
            ->orWhere('last_name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('phone', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        $stocks = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                's.store_id',
                's.product_id',
                'p.product_name',
                'st.store_name',
                's.quantity'
            )
            ->where('p.product_name', 'like', "%{$keyword}%")
            ->orWhere('st.store_name', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        $users = DB::table('users')
            ->select(
                'id',
                'name',
                'email',
                'role'
            )
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('role', 'like', "%{$keyword}%")
            ->limit(5)
            ->get();

        return response()->json([
            'products' => $products,
            'orders' => $orders,
            'customers' => $customers,
            'stocks' => $stocks,
            'users' => $users,
        ]);
    }
}