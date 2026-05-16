<?php

namespace App\Http\Controllers;

use App\Models\ScenarioRule;
use App\Services\ScenarioRuleService;
use Illuminate\Http\Request;

class ScenarioRuleController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $rules = ScenarioRule::latest()->paginate(10);

        return view('scenario-rules.index', compact('rules'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('scenario-rules.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'rule_name' => 'required|string|max:255',
            'condition_field' => 'required|string',
            'operator' => 'required|string',
            'condition_value' => 'required|string',
            'risk_type' => 'required|string|max:255',
            'severity' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ScenarioRule::create([
            'rule_name' => $request->rule_name,
            'module' => 'Customer',
            'condition_field' => $request->condition_field,
            'operator' => $request->operator,
            'condition_value' => $request->condition_value,
            'risk_type' => $request->risk_type,
            'severity' => $request->severity,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        logActivity(
            'Create Scenario Rule',
            'Scenario Builder',
            'Created scenario rule: '.$request->rule_name
        );

        return redirect('/scenario-rules')
            ->with('success', 'Scenario rule created successfully');
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $rule = ScenarioRule::findOrFail($id);

        return view('scenario-rules.edit', compact('rule'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $rule = ScenarioRule::findOrFail($id);

        $request->validate([
            'rule_name' => 'required|string|max:255',
            'condition_field' => 'required|string',
            'operator' => 'required|string',
            'condition_value' => 'required|string',
            'risk_type' => 'required|string|max:255',
            'severity' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $rule->update([
            'rule_name' => $request->rule_name,
            'condition_field' => $request->condition_field,
            'operator' => $request->operator,
            'condition_value' => $request->condition_value,
            'risk_type' => $request->risk_type,
            'severity' => $request->severity,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        logActivity(
            'Update Scenario Rule',
            'Scenario Builder',
            'Updated scenario rule: '.$request->rule_name
        );

        return redirect('/scenario-rules')
            ->with('success', 'Scenario rule updated successfully');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $rule = ScenarioRule::findOrFail($id);

        $rule->delete();

        return back()->with('success', 'Scenario rule deleted successfully');
    }

    public function run(ScenarioRuleService $service)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $created = $service->run();

        logActivity(
            'Run Scenario Builder',
            'Scenario Builder',
            'Scenario rules executed. Risk flags created: '.$created
        );

        return back()->with(
            'success',
            'Scenario rules executed. New risk flags created: '.$created
        );
    }
}