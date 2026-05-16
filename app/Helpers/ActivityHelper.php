<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

if (!function_exists('logActivity')) {

    function logActivity(
        $action,
        $module = null,
        $description = null,
        $oldValue = null,
        $newValue = null
    ) {
        ActivityLog::create([

            'user_name' => Auth::check()
                ? Auth::user()->name
                : 'System',

            'activity' => $description
                ?? $action,

            'user_id' => Auth::id(),

            'action' => $action,

            'module' => $module,

            'description' => $description,

            'old_value' => $oldValue
                ? json_encode($oldValue, JSON_UNESCAPED_UNICODE)
                : null,

            'new_value' => $newValue
                ? json_encode($newValue, JSON_UNESCAPED_UNICODE)
                : null,

            'ip_address' => Request::ip(),

            'user_agent' => Request::userAgent(),

        ]);
    }
}

use App\Models\Setting;

if (!function_exists('setting')) {

    function setting($key, $default = null)
    {
        return cache()->rememberForever(
            'setting_'.$key,
            function () use ($key, $default) {
                return Setting::where('key', $key)
                    ->value('value') ?? $default;
            }
        );
    }
}

if (!function_exists('calculateTrend')) {

    function calculateTrend($current, $previous)
    {
        $current = (float) $current;

        $previous = (float) $previous;

        if ($previous == 0 && $current > 0) {
            return [
                'percentage' => 100,
                'direction' => 'up',
                'label' => '+100%',
            ];
        }

        if ($previous == 0 && $current == 0) {
            return [
                'percentage' => 0,
                'direction' => 'flat',
                'label' => '0%',
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;

        $rounded = round($percentage, 1);

        if ($rounded > 0) {
            return [
                'percentage' => $rounded,
                'direction' => 'up',
                'label' => '+'.$rounded.'%',
            ];
        }

        if ($rounded < 0) {
            return [
                'percentage' => $rounded,
                'direction' => 'down',
                'label' => $rounded.'%',
            ];
        }

        return [
            'percentage' => 0,
            'direction' => 'flat',
            'label' => '0%',
        ];
    }
}

use App\Models\RiskFlag;

if (!function_exists('createRiskFlag')) {

    function createRiskFlag(
        $riskType,
        $severity,
        $module,
        $referenceId,
        $title,
        $description = null
    ) {
        RiskFlag::create([
            'risk_type' => $riskType,
            'severity' => $severity,
            'module' => $module,
            'reference_id' => $referenceId,
            'title' => $title,
            'description' => $description,
            'user_id' => Auth::id(),
            'user_name' => Auth::check()
                ? Auth::user()->name
                : 'System',
            'status' => 'Open',
        ]);
    }
}