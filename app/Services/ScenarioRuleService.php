<?php

namespace App\Services;

use App\Models\RiskFlag;
use App\Models\ScenarioRule;
use Illuminate\Support\Facades\DB;

class ScenarioRuleService
{
    public function run()
    {
        $created = 0;

        $rules = ScenarioRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($rule->module === 'Customer') {
                $created += $this->runCustomerRule($rule);
            }
        }

        return $created;
    }

    private function runCustomerRule($rule)
    {
        $created = 0;

        $customers = DB::table('sales.customers')->get();

        foreach ($customers as $customer) {

            $actualValue = $this->getCustomerValue(
                $customer,
                $rule->condition_field
            );

            if (
                $this->compare(
                    $actualValue,
                    $rule->operator,
                    $rule->condition_value
                )
            ) {
                $exists = RiskFlag::where('risk_type', $rule->risk_type)
                    ->where('module', 'Customer')
                    ->where('reference_id', $customer->customer_id)
                    ->where('status', 'Open')
                    ->exists();

                if (!$exists) {
                    RiskFlag::create([
                        'risk_type' => $rule->risk_type,
                        'severity' => $rule->severity,
                        'module' => 'Customer',
                        'reference_id' => $customer->customer_id,
                        'title' => $rule->title,
                        'description' => $rule->description
                            ?: 'Scenario rule matched for customer '
                                .$customer->first_name
                                .' '
                                .$customer->last_name,
                        'user_id' => auth()->id(),
                        'user_name' => auth()->user()->name ?? 'Scenario Engine',
                        'status' => 'Open',
                    ]);

                    $created++;
                }
            }
        }

        return $created;
    }

    private function getCustomerValue($customer, $field)
    {
        if ($field === 'risk_score') {
            return (int) ($customer->risk_score ?? 0);
        }

        if ($field === 'total_orders_90') {
            return DB::table('sales.orders')
                ->where('customer_id', $customer->customer_id)
                ->whereDate('order_date', '>=', now()->subDays(90)->toDateString())
                ->count();
        }

        if ($field === 'cancelled_orders_90') {
            return DB::table('sales.orders')
                ->where('customer_id', $customer->customer_id)
                ->where('status', 'Cancelled')
                ->whereDate('order_date', '>=', now()->subDays(90)->toDateString())
                ->count();
        }

        if ($field === 'total_spent_90') {
            return DB::table('sales.orders as o')
                ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
                ->where('o.customer_id', $customer->customer_id)
                ->whereDate('o.order_date', '>=', now()->subDays(90)->toDateString())
                ->sum(
                    DB::raw('oi.quantity * oi.list_price * (1 - oi.discount / 100)')
                );
        }

        if ($field === 'open_risk_flags') {
            return DB::table('risk_flags')
                ->where('module', 'Customer')
                ->where('reference_id', $customer->customer_id)
                ->where('status', 'Open')
                ->count();
        }

        if ($field === 'missing_profile') {
            return (
                empty($customer->email)
                || empty($customer->phone)
                || empty($customer->street)
                || empty($customer->city)
            ) ? 1 : 0;
        }

        return 0;
    }

    private function compare($actual, $operator, $expected)
    {
        $actual = is_numeric($actual) ? (float) $actual : $actual;
        $expected = is_numeric($expected) ? (float) $expected : $expected;

        return match ($operator) {
            '>=' => $actual >= $expected,
            '<=' => $actual <= $expected,
            '>'  => $actual > $expected,
            '<'  => $actual < $expected,
            '='  => $actual == $expected,
            '!=' => $actual != $expected,
            default => false,
        };
    }
}