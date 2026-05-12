<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    // INDEX

    public function index(Request $request)
    {

        $search = $request->search;

        $products = DB::table('production.products as p')

            ->join(
                'production.brands as b',
                'p.brand_id',
                '=',
                'b.brand_id'
            )

            ->join(
                'production.categories as c',
                'p.category_id',
                '=',
                'c.category_id'
            )

            ->select(
                'p.*',
                'b.brand_name',
                'c.category_name'
            )

            ->when($search, function($query) use ($search){

                $query->where(
                    'p.product_name',
                    'like',
                    "%{$search}%"
                );

            })

            ->paginate(10);

        return view(
            'products.index',
            compact('products')
        );
    }

    
    // SHOW DETAIL

    public function show($id)
    {

        $product = DB::table('production.products as p')

            ->join(
                'production.brands as b',
                'p.brand_id',
                '=',
                'b.brand_id'
            )

            ->join(
                'production.categories as c',
                'p.category_id',
                '=',
                'c.category_id'
            )

            ->select(
                'p.*',
                'b.brand_name',
                'c.category_name'
            )

            ->where(
                'p.product_id',
                $id
            )

            ->first();

        return view(
            'products.show',
            compact('product')
        );
    }

    // CREATE PAGE

    public function create()
    {

        $brands = DB::table('production.brands')->get();

        $categories = DB::table('production.categories')->get();

        return view(
            'products.create',
            compact('brands', 'categories')
        );
    }

    // STORE

    public function store(Request $request)
{

    $productId = DB::table('production.products')

        ->insertGetId([

            'product_name' =>
                $request->product_name,

            'brand_id' =>
                $request->brand_id,

            'category_id' =>
                $request->category_id,

            'model_year' =>
                $request->model_year,

            'list_price' =>
                $request->list_price

        ]);

    DB::table('production.stocks')

        ->insert([

            'store_id' => 1,

            'product_id' => $productId,

            'quantity' => 0

        ]);

    return redirect('/products')

        ->with(
            'success',
            'Product created successfully'
        );
}

    // EDIT PAGE

    public function edit($id)
{

    $product = DB::table(
        'production.products'
    )

    ->where(
        'product_id',
        $id
    )

    ->first();

    $brands = DB::table(
        'production.brands'
    )->get();

    $categories = DB::table(
        'production.categories'
    )->get();

    return view(
        'products.edit',
        compact(
            'product',
            'brands',
            'categories'
        )
    );
}

    // UPDATE

    public function update(Request $request, $id)
{

    DB::table('production.products')

        ->where(
            'product_id',
            $id
        )

        ->update([

            'product_name' =>
                $request->product_name,

            'brand_id' =>
                $request->brand_id,

            'category_id' =>
                $request->category_id,

            'model_year' =>
                $request->model_year,

            'list_price' =>
                $request->list_price

        ]);

    return redirect('/products')

        ->with(
            'success',
            'Product updated successfully'
        );
}

    // DELETE
    public function destroy($id)
    {
    
        // DELETE ORDER ITEMS FIRST
    
        DB::table('sales.order_items')
    
            ->where(
                'product_id',
                $id
            )
    
            ->delete();
    
        // DELETE STOCKS
    
        DB::table('production.stocks')
    
            ->where(
                'product_id',
                $id
            )
    
            ->delete();
    
        // DELETE PRODUCT
    
        DB::table('production.products')
    
            ->where(
                'product_id',
                $id
            )
    
            ->delete();
    
        return redirect('/products')
    
            ->with(
                'success',
                'Product deleted successfully'
            );
    }
}