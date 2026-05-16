<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CustomerSegmentationService;
use App\Services\CustomerRiskScoreService;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $customers = DB::table('sales.customers')
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            })
            ->orderBy('customer_id', 'desc')
            ->paginate(10);

        return view(
            'customers.index',
            compact(
                'customers',
                'search'
            )
        );
    }

    public function liveSearch(Request $request)
    {
        $search = $request->search;

        $customers = DB::table('sales.customers')
            ->select(
                'customer_id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'city',
                'segment',
                'risk_score',
                'risk_level'
            )
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            })
            ->orderBy('customer_id', 'desc')
            ->limit(20)
            ->get();

        return response()->json($customers);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        DB::table('sales.customers')
            ->insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'street' => $request->street,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'segment' => 'New',
                'risk_score' => 0,
                'risk_level' => 'Low',
            ]);

        logActivity(
            'Create Customer',
            'Customer',
            'Created customer: '.$request->first_name.' '.$request->last_name
        );

        return redirect('/customers')
            ->with(
                'success',
                'Customer added successfully'
            );
    }

    public function show($id)
    {
        $customer = DB::table('sales.customers')
            ->where('customer_id', $id)
            ->first();

        if (!$customer) {
            abort(404);
        }

        $orders = DB::table('sales.orders as o')
            ->leftJoin('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->selectRaw('
                o.order_id,
                o.order_date,
                o.status,
                SUM(
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ) as total
            ')
            ->where('o.customer_id', $id)
            ->groupBy(
                'o.order_id',
                'o.order_date',
                'o.status'
            )
            ->orderByDesc('o.order_id')
            ->get();

        $totalOrders = $orders->count();

        $totalSpent = $orders->sum('total');

        $lastOrderDate = $orders->max('order_date');

        $averageOrderValue = $totalOrders > 0
            ? round($totalSpent / $totalOrders)
            : 0;

        $cancelledOrders = $orders
            ->where('status', 'Cancelled')
            ->count();

        $cancelRate = $totalOrders > 0
            ? round(($cancelledOrders / $totalOrders) * 100, 1)
            : 0;

        $statusSummary = DB::table('sales.orders')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->where('customer_id', $id)
            ->groupBy('status')
            ->get();

        $favoriteProduct = DB::table('sales.order_items as oi')
            ->join('sales.orders as o', 'oi.order_id', '=', 'o.order_id')
            ->join('production.products as p', 'oi.product_id', '=', 'p.product_id')
            ->selectRaw('
                p.product_name,
                SUM(oi.quantity) as total_qty
            ')
            ->where('o.customer_id', $id)
            ->groupBy('p.product_name')
            ->orderByDesc('total_qty')
            ->first();

        $riskFlags = DB::table('risk_flags')
            ->where('module', 'Customer')
            ->where('reference_id', $id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $openRiskCount = DB::table('risk_flags')
            ->where('module', 'Customer')
            ->where('reference_id', $id)
            ->where('status', 'Open')
            ->count();

        $customerScore = 50;

        if (($customer->segment ?? 'New') == 'VIP') {
            $customerScore += 30;
        }

        if (($customer->segment ?? 'New') == 'Regular') {
            $customerScore += 15;
        }

        if (($customer->segment ?? 'New') == 'High Risk') {
            $customerScore -= 35;
        }

        if ($cancelRate >= 30) {
            $customerScore -= 20;
        }

        if ($totalSpent >= 10000000) {
            $customerScore += 15;
        }

        if ($openRiskCount > 0) {
            $customerScore -= 25;
        }

        $customerScore = max(0, min(100, $customerScore));

        $missingFields = [];

        if (empty($customer->email)) {
            $missingFields[] = 'Email';
        }

        if (empty($customer->phone)) {
            $missingFields[] = 'Phone';
        }

        if (empty($customer->street)) {
            $missingFields[] = 'Street Address';
        }

        if (empty($customer->city)) {
            $missingFields[] = 'City';
        }

        if (empty($customer->state)) {
            $missingFields[] = 'State';
        }

        if (empty($customer->zip_code)) {
            $missingFields[] = 'Zip Code';
        }

        $totalFields = 6;

        $completedFields = $totalFields - count($missingFields);

        $profilePercentage = round(($completedFields / $totalFields) * 100);

        return view(
            'customers.show',
            compact(
                'customer',
                'orders',
                'totalOrders',
                'totalSpent',
                'lastOrderDate',
                'statusSummary',
                'missingFields',
                'profilePercentage',
                'averageOrderValue',
                'cancelledOrders',
                'cancelRate',
                'favoriteProduct',
                'riskFlags',
                'openRiskCount',
                'customerScore'
            )
        );
    }

    public function runSegmentation(
        CustomerSegmentationService $service,
        CustomerRiskScoreService $riskScoreService
    ) {
        $service->run();

        $riskScoreService->calculate();

        logActivity(
            'Run Customer Segmentation',
            'Customer',
            'Customer segmentation and risk score executed'
        );

        return back()->with(
            'success',
            'Customer segmentation and risk score updated'
        );
    }

    public function runRiskScore(CustomerRiskScoreService $service)
    {
        $service->calculate();

        logActivity(
            'Run Customer Risk Score',
            'Customer',
            'Customer risk score calculation executed'
        );

        return back()->with(
            'success',
            'Customer risk score updated successfully'
        );
    }

    public function edit($id)
    {
        $customer = DB::table('sales.customers')
            ->where('customer_id', $id)
            ->first();

        if (!$customer) {
            abort(404);
        }

        return view(
            'customers.edit',
            compact('customer')
        );
    }

    public function update(Request $request, $id)
    {
        $oldCustomer = DB::table('sales.customers')
            ->where('customer_id', $id)
            ->first();

        DB::table('sales.customers')
            ->where('customer_id', $id)
            ->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'street' => $request->street,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
            ]);

        logActivity(
            'Update Customer',
            'Customer',
            'Updated customer: '.($oldCustomer->first_name ?? '').' '.($oldCustomer->last_name ?? '')
        );

        return redirect('/customers')
            ->with(
                'success',
                'Customer updated successfully'
            );
    }

    public function destroy($id)
    {
        $customer = DB::table('sales.customers')
            ->where('customer_id', $id)
            ->first();

        DB::table('sales.customers')
            ->where('customer_id', $id)
            ->delete();

        logActivity(
            'Delete Customer',
            'Customer',
            'Deleted customer: '.($customer->first_name ?? '').' '.($customer->last_name ?? '')
        );

        return redirect('/customers')
            ->with(
                'success',
                'Customer deleted successfully'
            );
    }
}