<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\OrderStatusTimeline;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    // ORDER LIST

    public function index(Request $request)
    {

        $search = $request->search;

        $orders = DB::table('sales.orders as o')

            ->join(
                'sales.customers as c',
                'o.customer_id',
                '=',
                'c.customer_id'
            )

            ->leftJoin(
                'sales.order_items as oi',
                'o.order_id',
                '=',
                'oi.order_id'
            )

            ->select(

                'o.order_id',
                'o.order_date',
                'o.status',

                DB::raw("
                    CONCAT(
                        c.first_name,
                        ' ',
                        c.last_name
                    ) as customer_name
                "),

                DB::raw("
                    IFNULL(
                        SUM(
                            oi.quantity *
                            oi.list_price *
                            (1 - oi.discount/100)
                        ),
                        0
                    ) as total_amount
                ")

            )

            ->when($search, function($query) use ($search) {

                $query->where(
                    'o.order_id',
                    'like',
                    "%{$search}%"
                );

            })

            ->groupBy(
                'o.order_id',
                'o.order_date',
                'o.status',
                'c.first_name',
                'c.last_name'
            )

            ->orderByDesc('o.order_id')

            ->paginate(10);

        return view(
            'orders.index',
            compact(
                'orders',
                'search'
            )
        );
    }
    

    // CREATE PAGE

    public function create()
    {

        $customers = DB::table('sales.customers')

            ->select(
                'customer_id',
                'first_name',
                'last_name',
                'email'
            )

            ->groupBy(
                'customer_id',
                'first_name',
                'last_name',
                'email'
            )

            ->get();

        $products = DB::table('production.products')->get();

        return view(
            'orders.create',
            compact(
                'customers',
                'products'
            )
        );
    }


    /*
|--------------------------------------------------------------------------
| EXPORT CSV
|--------------------------------------------------------------------------
*/

public function exportCsv()
{

    $fileName = 'orders.csv';

    $orders = DB::table('sales.orders as o')

        ->join(
            'sales.customers as c',
            'o.customer_id',
            '=',
            'c.customer_id'
        )

        ->leftJoin(
            'sales.order_items as oi',
            'o.order_id',
            '=',
            'oi.order_id'
        )

        ->select(

            'o.order_id',
            'o.order_date',
            'o.status',

            DB::raw("
                CONCAT(
                    c.first_name,
                    ' ',
                    c.last_name
                ) as customer_name
            "),

            DB::raw("
                IFNULL(
                    SUM(
                        oi.quantity *
                        oi.list_price
                    ),
                    0
                ) as total_amount
            ")

        )

        ->groupBy(
            'o.order_id',
            'o.order_date',
            'o.status',
            'c.first_name',
            'c.last_name'
        )

        ->orderByDesc('o.order_id')

        ->get();

    $headers = [

        "Content-type" => "text/csv",
        "Content-Disposition" =>
            "attachment; filename=$fileName",

        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate",
        "Expires" => "0"

    ];

    $columns = [

        'Order ID',
        'Date',
        'Customer',
        'Total',
        'Status'

    ];

    $callback = function() use (
        $orders,
        $columns
    ) {

        $file = fopen('php://output', 'w');

        fputcsv($file, $columns);

        foreach ($orders as $order) {

            fputcsv($file, [

                $order->order_id,
                $order->order_date,
                $order->customer_name,
                $order->total_amount,
                $order->status

            ]);
        }

        fclose($file);
    };

    return response()->stream(
        $callback,
        200,
        $headers
    );
}

    // STORE ORDER

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | CREATE NEW CUSTOMER
            |--------------------------------------------------------------------------
            */

            if ($request->new_customer_name) {

                $customerId = DB::table('sales.customers')

                    ->insertGetId([

                        'first_name' =>
                            $request->new_customer_name,

                        'last_name' => '',

                        'phone' =>
                            $request->new_customer_phone,

                        'email' =>
                            $request->new_customer_email,

                        'street' => '',

                        'city' =>
                            $request->new_customer_city,

                        'state' => '',

                        'zip_code' => ''

                    ]);

            } else {

                $customerId = $request->customer_id;
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT ORDER
            |--------------------------------------------------------------------------
            */

            $orderId = DB::table('sales.orders')

                ->insertGetId([

                    'customer_id' => $customerId,

                    'order_status' => 1,

                    'order_date' => now(),

                    'required_date' => now(),

                    'shipped_date' => now(),

                    'store_id' => 1,

                    'staff_id' => 1,

                    'status' => 'Pending'

                ]);

            /*
            |--------------------------------------------------------------------------
            | INSERT MULTIPLE ORDER ITEMS
            |--------------------------------------------------------------------------
            */

            foreach ($request->products as $index => $productId) {

                // GET PRODUCT

                $product = DB::table('production.products')

                    ->where(
                        'product_id',
                        $productId
                    )

                    ->first();

                // GET QTY

                $qty = $request->quantities[$index];

                // INSERT ORDER ITEM

                DB::table('sales.order_items')

                    ->insert([

                        'order_id' => $orderId,

                        'product_id' => $productId,

                        'quantity' => $qty,

                        'list_price' => $product->list_price,

                        'discount' => 0

                    ]);

                // REDUCE STOCK

                DB::table('production.stocks')

                    ->where(
                        'product_id',
                        $productId
                    )

                    ->decrement(
                        'quantity',
                        $qty
                    );
            }

            DB::commit();

            OrderStatusTimeline::create([
                'order_id' => $orderId,
                'status' => 'Pending',
                'title' => 'Order Created',
                'description' => 'Order has been created and is waiting for payment.',
                'user_id' => Auth::id(),
                'user_name' => Auth::check()
                    ? Auth::user()->name
                    : 'System',
            ]);
            
            logActivity(
                'Create Order',
                'Order',
                'Created Order #'.$orderId
            );

ActivityLog::create([

    'user_name' => auth()->user()->name,

    'activity' => 'Created Order #'.$orderId

]);

return redirect('/orders')

    ->with(
        'success',
        'Order created successfully'
    );

        } catch (\Exception $e) {

            DB::rollBack();

            return $e->getMessage();
        }
    }

    // ORDER DETAIL

    public function show($id)
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
    o.status,

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
                oi.discount,

                (
                    oi.quantity *
                    oi.list_price *
                    (1-oi.discount)
                ) as subtotal
            ')

            ->where(
                'oi.order_id',
                $id
            )

            ->get();

        $grandTotal = $items->sum('subtotal');

        $timelines = OrderStatusTimeline::where('order_id', $id)
    ->orderBy('created_at')
    ->get();

        return view(
            'orders.show',
            compact(
                'order',
                'items',
                'timelines',
                'grandTotal'
            )
        );
    }
}