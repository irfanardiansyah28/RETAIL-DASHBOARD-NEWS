<?php

namespace App\Http\Controllers;

use App\Models\OrderStatusTimeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function pay($id)
    {
        DB::beginTransaction();

        try {

            $total = DB::table('sales.order_items')
                ->where('order_id', $id)
                ->selectRaw('
                    SUM(
                        quantity *
                        list_price *
                        (1 - discount / 100)
                    ) as total
                ')
                ->first();

            DB::table('sales.payments')
                ->insert([
                    'order_id' => $id,
                    'payment_date' => now(),
                    'amount' => $total->total ?? 0,
                    'payment_method' => 'Transfer',
                    'status' => 'Paid',
                ]);

            DB::table('sales.orders')
                ->where('order_id', $id)
                ->update([
                    'status' => 'Paid',
                ]);

            OrderStatusTimeline::create([
                'order_id' => $id,
                'status' => 'Paid',
                'title' => 'Order Paid',
                'description' => 'Payment has been completed successfully.',
                'user_id' => Auth::id(),
                'user_name' => Auth::check()
                    ? Auth::user()->name
                    : 'System',
            ]);

            logActivity(
                'Pay Order',
                'Order',
                'Payment success for Order #'.$id
            );


            if (($total->total ?? 0) >= 10000000) {

                createRiskFlag(
                    'High Order Value',
                    'High',
                    'Order',
                    $id,
                    'High value order detected',
                    'Order #'.$id.' amount is '.number_format($total->total, 0, ',', '.')
                );
            
            }
            
            DB::commit();

            return back()
                ->with('success', 'Payment success');

        } catch (\Exception $e) {

            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function cancel($id)
    {
        DB::beginTransaction();

        try {

            $items = DB::table('sales.order_items')
                ->where('order_id', $id)
                ->get();

            foreach ($items as $item) {

                DB::table('production.stocks')
                    ->where('product_id', $item->product_id)
                    ->increment('quantity', $item->quantity);

            }

            DB::table('sales.orders')
                ->where('order_id', $id)
                ->update([
                    'status' => 'Cancelled',
                ]);

            OrderStatusTimeline::create([
                'order_id' => $id,
                'status' => 'Cancelled',
                'title' => 'Order Cancelled',
                'description' => 'Order has been cancelled and stock has been restored.',
                'user_id' => Auth::id(),
                'user_name' => Auth::check()
                    ? Auth::user()->name
                    : 'System',
            ]);

            logActivity(
                'Cancel Order',
                'Order',
                'Cancelled Order #'.$id
            );

            createRiskFlag(
                'Order Cancelled',
                'Medium',
                'Order',
                $id,
                'Order #'.$id.' was cancelled',
                'Cancelled order may require review, especially if repeated frequently.'
            );

            DB::commit();

            return redirect('/orders')
                ->with('success', 'Order cancelled successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function ship($id)
    {
        DB::table('sales.orders')
            ->where('order_id', $id)
            ->update([
                'status' => 'Shipped',
            ]);

        OrderStatusTimeline::create([
            'order_id' => $id,
            'status' => 'Shipped',
            'title' => 'Order Shipped',
            'description' => 'Order has been shipped to the customer.',
            'user_id' => Auth::id(),
            'user_name' => Auth::check()
                ? Auth::user()->name
                : 'System',
        ]);

        logActivity(
            'Ship Order',
            'Order',
            'Shipped Order #'.$id
        );

        return back()
            ->with('success', 'Order shipped successfully');
    }

    public function complete($id)
    {
        DB::table('sales.orders')
            ->where('order_id', $id)
            ->update([
                'status' => 'Completed',
            ]);

        OrderStatusTimeline::create([
            'order_id' => $id,
            'status' => 'Completed',
            'title' => 'Order Completed',
            'description' => 'Order has been completed successfully.',
            'user_id' => Auth::id(),
            'user_name' => Auth::check()
                ? Auth::user()->name
                : 'System',
        ]);

        logActivity(
            'Complete Order',
            'Order',
            'Completed Order #'.$id
        );

        return back()
            ->with('success', 'Order completed successfully');
    }
}