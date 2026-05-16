<?php

namespace App\Http\Controllers;

use App\Models\InvestigationCase;
use App\Models\RiskFlag;
use App\Services\DynamicFraudPatternService;
use App\Services\FraudDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiskFlagController extends Controller
{
    private function createInvestigationCase(RiskFlag $riskFlag)
    {
        $exists = InvestigationCase::where(
            'risk_flag_id',
            $riskFlag->id
        )->exists();

        if ($exists) {
            return;
        }

        InvestigationCase::create([
            'risk_flag_id' => $riskFlag->id,
            'case_number' => 'CASE-'.$riskFlag->id.'-'.now()->format('YmdHis'),
            'title' => $riskFlag->title,
            'description' => $riskFlag->description,
            'priority' => $riskFlag->severity,
            'status' => 'Open',
            'assigned_to' => null,
            'investigation_note' => null,
        ]);
    }

    private function syncOpenRiskFlagsToCases()
    {
        $riskFlags = RiskFlag::where('status', 'Open')
            ->get();

        foreach ($riskFlags as $riskFlag) {
            $this->createInvestigationCase($riskFlag);
        }
    }

    public function show($id)
    {
        $risk = RiskFlag::findOrFail($id);

        $this->createInvestigationCase($risk);

        $case = InvestigationCase::where(
            'risk_flag_id',
            $risk->id
        )->first();

        $details = [];

        /*
        |--------------------------------------------------------------------------
        | Similar phone pattern
        |--------------------------------------------------------------------------
        */

        if ($risk->risk_type == 'Similar Phone Pattern') {

            preg_match(
                '/prefix\s(.+?)\s/i',
                $risk->description,
                $matches
            );

            $prefix = $matches[1] ?? '';

            $details = DB::table('sales.customers')
                ->where(
                    'phone',
                    'like',
                    $prefix.'%'
                )
                ->select(
                    'customer_id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone'
                )
                ->get();

        }

        /*
        |--------------------------------------------------------------------------
        | Large stock decrease
        |--------------------------------------------------------------------------
        */

        if ($risk->risk_type == 'Large Stock Decrease') {

            $details = DB::table('activity_logs')
                ->where(
                    'description',
                    'like',
                    '%decreased%'
                )
                ->latest()
                ->limit(10)
                ->get();

        }

        /*
        |--------------------------------------------------------------------------
        | Repeated cancel
        |--------------------------------------------------------------------------
        */

        if ($risk->risk_type == 'Repeated Cancelled Orders Pattern') {

            $details = DB::table('sales.orders')
                ->where(
                    'status',
                    'Cancelled'
                )
                ->latest(
                    'order_date'
                )
                ->limit(10)
                ->get();

        }

        return view(
            'risk_flags.show',
            compact(
                'risk',
                'details',
                'case'
            )
        );
    }

    public function scanDynamicPattern(
        DynamicFraudPatternService $dynamicFraudPatternService
    ) {
        $created = $dynamicFraudPatternService->scan();

        $this->syncOpenRiskFlagsToCases();

        logActivity(
            'Run Dynamic Fraud Pattern Detection',
            'Risk',
            'Dynamic fraud pattern scan completed. New risk flags: '.$created
        );

        return redirect('/risk-flags')
            ->with(
                'success',
                'Dynamic fraud pattern scan completed. New risk flags created: '.$created
            );
    }

    public function index(Request $request)
    {
        $this->syncOpenRiskFlagsToCases();

        $search = $request->search;

        $severity = $request->severity;

        $status = $request->status;

        $riskFlags = RiskFlag::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('risk_type', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");
            })
            ->when($severity, function ($query) use ($severity) {
                $query->where('severity', $severity);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $openCount = RiskFlag::where('status', 'Open')->count();

        $highCount = RiskFlag::where('severity', 'High')
            ->where('status', 'Open')
            ->count();

        $mediumCount = RiskFlag::where('severity', 'Medium')
            ->where('status', 'Open')
            ->count();

        $lowCount = RiskFlag::where('severity', 'Low')
            ->where('status', 'Open')
            ->count();

        return view(
            'risk_flags.index',
            compact(
                'riskFlags',
                'search',
                'severity',
                'status',
                'openCount',
                'highCount',
                'mediumCount',
                'lowCount'
            )
        );
    }

    public function close($id)
    {
        $riskFlag = RiskFlag::findOrFail($id);

        $riskFlag->update([
            'status' => 'Closed',
        ]);

        InvestigationCase::where(
            'risk_flag_id',
            $riskFlag->id
        )->update([
            'status' => 'Resolved',
        ]);

        logActivity(
            'Close Risk Flag',
            'Risk',
            'Closed risk flag: '.$riskFlag->title
        );

        return back()
            ->with('success', 'Risk flag closed successfully');
    }

    public function reopen($id)
    {
        $riskFlag = RiskFlag::findOrFail($id);

        $riskFlag->update([
            'status' => 'Open',
        ]);

        $this->createInvestigationCase($riskFlag);

        InvestigationCase::where(
            'risk_flag_id',
            $riskFlag->id
        )->update([
            'status' => 'Open',
        ]);

        logActivity(
            'Reopen Risk Flag',
            'Risk',
            'Reopened risk flag: '.$riskFlag->title
        );

        return back()
            ->with('success', 'Risk flag reopened successfully');
    }

    public function scan(FraudDetectionService $fraudDetectionService)
    {
        $created = $fraudDetectionService->scan();

        $this->syncOpenRiskFlagsToCases();

        logActivity(
            'Run Fraud Scan',
            'Risk',
            'Manual fraud scan completed. New risk flags: '.$created
        );

        return redirect('/risk-flags')
            ->with(
                'success',
                'Fraud scan completed. New risk flags created: '.$created
            );
    }
}