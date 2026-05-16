<?php

namespace App\Http\Controllers;

use App\Models\RulePerformance;

class RuleAnalyticsController extends Controller
{
    public function index()
    {
        $rules=

        RulePerformance::orderByDesc(
            'trigger_count'
        )

        ->paginate(20);

        return view(
            'analytics.rule-performance',
            compact(
                'rules'
            )
        );
    }
}