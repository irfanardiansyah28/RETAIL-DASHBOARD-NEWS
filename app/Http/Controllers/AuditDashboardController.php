<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->toDateString();

        $endDate = $request->end_date ?? now()->toDateString();

        $totalActivities = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->count();

        $todayActivities = DB::table('activity_logs')
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $stockUpdates = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('module', 'Stock')
            ->count();

        $orderActivities = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('module', 'Order')
            ->count();

        $deleteActivities = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('action', 'like', '%Delete%')
            ->count();

        $settingsChanges = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('module', 'Settings')
            ->count();

        $mostActiveUsers = DB::table('activity_logs')
            ->select(
                'user_name',
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('user_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $activityByModule = DB::table('activity_logs')
            ->select(
                'module',
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereNotNull('module')
            ->groupBy('module')
            ->orderByDesc('total')
            ->get();

        $activityByAction = DB::table('activity_logs')
            ->select(
                'action',
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereNotNull('action')
            ->groupBy('action')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $highRiskActivities = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where(function ($query) {
                $query->where('action', 'like', '%Delete%')
                    ->orWhere('action', 'like', '%Cancel%')
                    ->orWhere('action', 'like', '%Update Stock%')
                    ->orWhere('action', 'like', '%Update User%')
                    ->orWhere('action', 'like', '%Update Settings%');
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentActivities = DB::table('activity_logs')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $latestStockMovements = DB::table('stock_movements')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $moduleLabels = $activityByModule
            ->pluck('module')
            ->toArray();

        $moduleData = $activityByModule
            ->pluck('total')
            ->toArray();

        $actionLabels = $activityByAction
            ->pluck('action')
            ->toArray();

        $actionData = $activityByAction
            ->pluck('total')
            ->toArray();

        return view(
            'audit.dashboard',
            compact(
                'startDate',
                'endDate',
                'totalActivities',
                'todayActivities',
                'stockUpdates',
                'orderActivities',
                'deleteActivities',
                'settingsChanges',
                'mostActiveUsers',
                'activityByModule',
                'activityByAction',
                'highRiskActivities',
                'recentActivities',
                'latestStockMovements',
                'moduleLabels',
                'moduleData',
                'actionLabels',
                'actionData'
            )
        );
    }
}