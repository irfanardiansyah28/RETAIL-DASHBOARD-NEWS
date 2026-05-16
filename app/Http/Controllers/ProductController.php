<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = DB::table('production.products as p')
            ->join('production.brands as b', 'p.brand_id', '=', 'b.brand_id')
            ->join('production.categories as c', 'p.category_id', '=', 'c.category_id')
            ->select('p.*', 'b.brand_name', 'c.category_name')
            ->when($search, function($query) use ($search) {
                $query->where('p.product_name', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('products.index', compact('products', 'search'));
    }

    public function liveSearch(Request $request)
    {
        $search = $request->search;

        $products = DB::table('production.products as p')
            ->join('production.brands as b', 'p.brand_id', '=', 'b.brand_id')
            ->join('production.categories as c', 'p.category_id', '=', 'c.category_id')
            ->select('p.*', 'b.brand_name', 'c.category_name')
            ->when($search, function($query) use ($search) {
                $query->where('p.product_name', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    public function show($id)
    {
        $product = DB::table('production.products as p')
            ->join('production.brands as b', 'p.brand_id', '=', 'b.brand_id')
            ->join('production.categories as c', 'p.category_id', '=', 'c.category_id')
            ->select(
                'p.*',
                'b.brand_name',
                'c.category_name'
            )
            ->where('p.product_id', $id)
            ->first();

        if (!$product) {
            abort(404);
        }

        $stockByStore = DB::table('production.stocks as s')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                's.store_id',
                'st.store_name',
                's.quantity'
            )
            ->where('s.product_id', $id)
            ->orderBy('st.store_name')
            ->get();

        $totalStock = $stockByStore->sum('quantity');

        $salesSummary = DB::table('sales.order_items as oi')
            ->join('sales.orders as o', 'oi.order_id', '=', 'o.order_id')
            ->where('oi.product_id', $id)
            ->selectRaw('
                SUM(oi.quantity) as total_sold,
                SUM(
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as total_revenue,
                COUNT(DISTINCT oi.order_id) as total_orders
            ')
            ->first();

        $recentOrders = DB::table('sales.order_items as oi')
            ->join('sales.orders as o', 'oi.order_id', '=', 'o.order_id')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                o.order_id,
                o.order_date,
                o.status,
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                oi.quantity,
                oi.list_price,
                oi.discount,
                (
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as subtotal
            ')
            ->where('oi.product_id', $id)
            ->orderByDesc('o.order_id')
            ->limit(10)
            ->get();

        $topCustomers = DB::table('sales.order_items as oi')
            ->join('sales.orders as o', 'oi.order_id', '=', 'o.order_id')
            ->join('sales.customers as c', 'o.customer_id', '=', 'c.customer_id')
            ->selectRaw('
                CONCAT(c.first_name, " ", c.last_name) as customer_name,
                SUM(oi.quantity) as total_qty,
                SUM(
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as total_spent
            ')
            ->where('oi.product_id', $id)
            ->groupBy(
                'c.first_name',
                'c.last_name'
            )
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        $stockMovements = DB::table('stock_movements')
            ->where('product_id', $id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $salesChart = DB::table('sales.order_items as oi')
            ->join('sales.orders as o', 'oi.order_id', '=', 'o.order_id')
            ->selectRaw('
                DATE(o.order_date) as date,
                SUM(oi.quantity) as total_qty
            ')
            ->where('oi.product_id', $id)
            ->whereDate('o.order_date', '>=', now()->subDays(29)->toDateString())
            ->groupByRaw('DATE(o.order_date)')
            ->orderByRaw('DATE(o.order_date)')
            ->get();

        $salesChartLabels = $salesChart
            ->pluck('date')
            ->toArray();

        $salesChartData = $salesChart
            ->pluck('total_qty')
            ->toArray();

        return view(
            'products.show',
            compact(
                'product',
                'stockByStore',
                'totalStock',
                'salesSummary',
                'recentOrders',
                'topCustomers',
                'stockMovements',
                'salesChartLabels',
                'salesChartData'
            )
        );
    }

    public function create()
    {
        $brands = DB::table('production.brands')->get();

        $categories = DB::table('production.categories')->get();

        return view('products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $productId = DB::table('production.products')
            ->insertGetId([
                'product_name' => $request->product_name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'model_year' => $request->model_year,
                'list_price' => $request->list_price,
            ]);

        DB::table('production.stocks')
            ->insert([
                'store_id' => 1,
                'product_id' => $productId,
                'quantity' => 0,
            ]);

        $newProduct = DB::table('production.products')
            ->where('product_id', $productId)
            ->first();

        logActivity(
            'Create Product',
            'Product',
            'Product created: '.$request->product_name,
            null,
            $newProduct
        );

        return redirect('/products')
            ->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = DB::table('production.products')
            ->where('product_id', $id)
            ->first();

        $brands = DB::table('production.brands')->get();

        $categories = DB::table('production.categories')->get();

        return view('products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $oldProduct = DB::table('production.products')
            ->where('product_id', $id)
            ->first();

        DB::table('production.products')
            ->where('product_id', $id)
            ->update([
                'product_name' => $request->product_name,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'model_year' => $request->model_year,
                'list_price' => $request->list_price,
            ]);

        $newProduct = DB::table('production.products')
            ->where('product_id', $id)
            ->first();

        logActivity(
            'Update Product',
            'Product',
            'Product updated: '.$request->product_name,
            $oldProduct,
            $newProduct
        );

        return redirect('/products')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $oldProduct = DB::table('production.products')
            ->where('product_id', $id)
            ->first();

        DB::table('sales.order_items')
            ->where('product_id', $id)
            ->delete();

        DB::table('production.stocks')
            ->where('product_id', $id)
            ->delete();

        DB::table('production.products')
            ->where('product_id', $id)
            ->delete();

        logActivity(
            'Delete Product',
            'Product',
            'Product deleted: '.($oldProduct->product_name ?? 'Unknown Product'),
            $oldProduct,
            null
        );

        return redirect('/products')
            ->with('success', 'Product deleted successfully');
    }
}