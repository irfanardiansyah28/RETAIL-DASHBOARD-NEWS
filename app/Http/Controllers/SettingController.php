<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:100',
            'currency' => 'required|string|max:10',
            'low_stock_threshold' => 'required|integer|min:1',
            'tax_percentage' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->only([
            'store_name',
            'currency',
            'low_stock_threshold',
            'tax_percentage',
        ]) as $key => $value) {

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        cache()->flush();

        logActivity(
            'Update Settings',
            'Settings',
            'System settings updated',
            null,
            $request->only([
                'store_name',
                'currency',
                'low_stock_threshold',
                'tax_percentage',
            ])
        );

        createRiskFlag(
            'Settings Changed',
            'Medium',
            'Settings',
            null,
            'System settings changed',
            'Critical system configuration was updated.'
        );

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
}