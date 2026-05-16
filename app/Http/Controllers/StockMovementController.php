<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $startDate = $request->start_date;

        $endDate = $request->end_date;

        $movements = StockMovement::query()
            ->when($search, function ($query) use ($search) {
                $query->where('product_name', 'like', "%{$search}%")
                    ->orWhere('store_name', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'stock_movements.index',
            compact(
                'movements',
                'search',
                'startDate',
                'endDate'
            )
        );
    }
}