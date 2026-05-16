<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalRequest;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

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
            ->when($search, function($query) use ($search) {
                $query->where('p.product_name', 'like', "%{$search}%")
                    ->orWhere('st.store_name', 'like', "%{$search}%");
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

    public function liveSearch(Request $request)
    {
        $search = $request->search;

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
            ->when($search, function($query) use ($search) {
                $query->where('p.product_name', 'like', "%{$search}%")
                    ->orWhere('st.store_name', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get();

        return response()->json($stocks);
    }

    public function edit($store_id, $product_id)
    {
        $stock = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                's.*',
                'p.product_name',
                'st.store_name'
            )
            ->where('s.store_id', $store_id)
            ->where('s.product_id', $product_id)
            ->first();

        if (!$stock) {
            abort(404);
        }

        return view(
            'stocks.edit',
            compact('stock')
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        $oldStock = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                's.*',
                'p.product_name',
                'st.store_name'
            )
            ->where('s.store_id', $request->store_id)
            ->where('s.product_id', $request->product_id)
            ->first();

        if (!$oldStock) {
            abort(404);
        }

        $oldQuantity = (int) $oldStock->quantity;

        $newQuantity = (int) $request->quantity;

        $difference = $newQuantity - $oldQuantity;

        if ($difference <= -50 && auth()->user()->role != 'admin') {

            ApprovalRequest::create([
                'request_type' => 'Large Stock Decrease',
                'module' => 'Stock',
                'reference_id' => $request->product_id,
                'title' => 'Large stock decrease requires approval',
                'description' => $oldStock->product_name
                    .' at '
                    .$oldStock->store_name
                    .' requested decrease from '
                    .$oldQuantity
                    .' to '
                    .$newQuantity,
                'payload' => [
                    'store_id' => $request->store_id,
                    'product_id' => $request->product_id,
                    'store_name' => $oldStock->store_name,
                    'product_name' => $oldStock->product_name,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'difference' => $difference,
                ],
                'requested_by' => auth()->id(),
                'requested_by_name' => auth()->user()->name,
                'status' => 'Pending',
            ]);
        
            createRiskFlag(
                'Approval Required',
                'High',
                'Stock',
                $request->product_id,
                'Large stock decrease pending approval',
                $oldStock->product_name
                    .' stock decrease requires admin approval.'
            );
        
            logActivity(
                'Request Stock Decrease Approval',
                'Approval',
                'Requested approval for large stock decrease: '
                    .$oldStock->product_name
            );
        
            return redirect('/stocks')
                ->with('success', 'Large stock decrease request has been submitted for approval');
        }

        DB::table('production.stocks')
            ->where('store_id', $request->store_id)
            ->where('product_id', $request->product_id)
            ->update([
                'quantity' => $newQuantity
            ]);

        $newStock = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                's.*',
                'p.product_name',
                'st.store_name'
            )
            ->where('s.store_id', $request->store_id)
            ->where('s.product_id', $request->product_id)
            ->first();

        StockMovement::create([
            'store_id' => $request->store_id,
            'product_id' => $request->product_id,
            'store_name' => $oldStock->store_name,
            'product_name' => $oldStock->product_name,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'difference' => $difference,
            'user_id' => Auth::id(),
            'user_name' => Auth::check()
                ? Auth::user()->name
                : 'System',
            'notes' => 'Manual stock update from Stock Management',
        ]);

        if ($difference <= -50) {

            createRiskFlag(
                'Large Stock Decrease',
                'High',
                'Stock',
                $request->product_id,
                'Large stock decrease detected',
                $oldStock->product_name
                    .' at '
                    .$oldStock->store_name
                    .' decreased by '
                    .abs($difference)
                    .' units.'
            );
        
        }

        logActivity(
            'Update Stock',
            'Stock',
            'Stock updated: '
                . $oldStock->product_name
                . ' at '
                . $oldStock->store_name
                . ' from '
                . $oldQuantity
                . ' to '
                . $newQuantity,
            $oldStock,
            $newStock
        );

        return redirect('/stocks')
            ->with('success', 'Stock updated successfully');
    }
}