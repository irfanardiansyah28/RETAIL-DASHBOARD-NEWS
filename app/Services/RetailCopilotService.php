<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RetailCopilotService
{
    public function answer($question)
    {
        $question = strtolower($question);

        if (
            str_contains($question, 'priority') ||
            str_contains($question, 'prioritas') ||
            str_contains($question, 'today') ||
            str_contains($question, 'hari ini')
        ) {
            return $this->prioritySummary();
        }

        if (
            str_contains($question, 'risk') ||
            str_contains($question, 'fraud') ||
            str_contains($question, 'suspicious')
        ) {
            return $this->riskSummary();
        }

        if (
            str_contains($question, 'stock') ||
            str_contains($question, 'stok') ||
            str_contains($question, 'restock') ||
            str_contains($question, 'inventory')
        ) {
            return $this->stockSummary();
        }

        if (
            str_contains($question, 'customer') ||
            str_contains($question, 'pelanggan')
        ) {
            return $this->customerSummary();
        }

        if (
            str_contains($question, 'store') ||
            str_contains($question, 'toko') ||
            str_contains($question, 'branch')
        ) {
            return $this->storeSummary();
        }

        if (
            str_contains($question, 'revenue') ||
            str_contains($question, 'sales') ||
            str_contains($question, 'penjualan')
        ) {
            return $this->revenueSummary();
        }

        return $this->defaultAnswer();
    }

    private function prioritySummary()
    {
        $openRisk = DB::table('risk_flags')
            ->where('status', 'Open')
            ->count();

        $highRiskCustomers = DB::table('sales.customers')
            ->where('risk_level', 'High')
            ->count();

        $lowStocks = DB::table('production.stocks')
            ->where('quantity', '<=', (int) setting('low_stock_threshold', 10))
            ->count();

        $pendingOrders = DB::table('sales.orders')
            ->where('status', 'Pending')
            ->count();

        $pendingApprovals = DB::table('approval_requests')
            ->where('status', 'Pending')
            ->count();

        return [
            'title' => 'Today Priority Summary',
            'items' => [
                'Review '.$openRisk.' open risk flag(s).',
                'Check '.$highRiskCustomers.' high risk customer(s).',
                'Restock or review '.$lowStocks.' low stock product(s).',
                'Follow up '.$pendingOrders.' pending order(s).',
                'Process '.$pendingApprovals.' pending approval request(s).',
            ],
        ];
    }

    private function riskSummary()
    {
        $highRisk = DB::table('risk_flags')
            ->where('status', 'Open')
            ->where('severity', 'High')
            ->count();

        $mediumRisk = DB::table('risk_flags')
            ->where('status', 'Open')
            ->where('severity', 'Medium')
            ->count();

        $latestRisks = DB::table('risk_flags')
            ->where('status', 'Open')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $items = [
            'There are '.$highRisk.' high severity open risk flag(s).',
            'There are '.$mediumRisk.' medium severity open risk flag(s).',
        ];

        foreach ($latestRisks as $risk) {
            $items[] = $risk->risk_type.' - '.$risk->title;
        }

        return [
            'title' => 'Risk & Fraud Summary',
            'items' => $items,
        ];
    }

    private function stockSummary()
    {
        $lowStockLimit = (int) setting('low_stock_threshold', 10);

        $lowStocks = DB::table('production.stocks as s')
            ->join('production.products as p', 's.product_id', '=', 'p.product_id')
            ->join('sales.stores as st', 's.store_id', '=', 'st.store_id')
            ->select(
                'p.product_name',
                'st.store_name',
                's.quantity'
            )
            ->where('s.quantity', '<=', $lowStockLimit)
            ->orderBy('s.quantity', 'asc')
            ->limit(5)
            ->get();

        $items = [];

        if ($lowStocks->count() == 0) {
            $items[] = 'Inventory condition looks healthy. No low stock detected.';
        }

        foreach ($lowStocks as $stock) {
            $items[] = $stock->product_name
                .' at '
                .$stock->store_name
                .' only has '
                .$stock->quantity
                .' stock left.';
        }

        return [
            'title' => 'Inventory Recommendation',
            'items' => $items,
        ];
    }

    private function customerSummary()
    {
        $highRiskCustomers = DB::table('sales.customers')
            ->where('risk_level', 'High')
            ->orderByDesc('risk_score')
            ->limit(5)
            ->get();

        $vipCustomers = DB::table('sales.customers')
            ->where('segment', 'VIP')
            ->count();

        $items = [
            'There are '.$vipCustomers.' VIP customer(s).',
        ];

        if ($highRiskCustomers->count() == 0) {
            $items[] = 'No high risk customer found.';
        }

        foreach ($highRiskCustomers as $customer) {
            $items[] = $customer->first_name
                .' '
                .$customer->last_name
                .' has risk score '
                .$customer->risk_score
                .' / 100.';
        }

        return [
            'title' => 'Customer Intelligence',
            'items' => $items,
        ];
    }

    private function storeSummary()
    {
        $stores = DB::table('sales.stores as s')
            ->leftJoin('sales.orders as o', 's.store_id', '=', 'o.store_id')
            ->leftJoin('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->selectRaw('
                s.store_name,
                COUNT(DISTINCT o.order_id) as total_orders,
                COALESCE(
                    SUM(
                        oi.quantity *
                        oi.list_price *
                        (1 - oi.discount / 100)
                    ),
                    0
                ) as total_revenue
            ')
            ->whereDate('o.order_date', '>=', now()->subDays(30)->toDateString())
            ->groupBy('s.store_name')
            ->orderByDesc('total_revenue')
            ->limit(3)
            ->get();

        $items = [];

        if ($stores->count() == 0) {
            $items[] = 'No store performance data found.';
        }

        foreach ($stores as $store) {
            $items[] = $store->store_name
                .' generated '
                .setting('currency', 'Rp')
                .' '
                .number_format($store->total_revenue, 0, ',', '.')
                .' from '
                .$store->total_orders
                .' order(s).';
        }

        return [
            'title' => 'Store Performance Insight',
            'items' => $items,
        ];
    }

    private function revenueSummary()
    {
        $todayRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->whereDate('o.order_date', now()->toDateString())
            ->sum(
                DB::raw('
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ')
            );

        $monthRevenue = DB::table('sales.orders as o')
            ->join('sales.order_items as oi', 'o.order_id', '=', 'oi.order_id')
            ->whereMonth('o.order_date', now()->month)
            ->whereYear('o.order_date', now()->year)
            ->sum(
                DB::raw('
                    oi.quantity *
                    oi.list_price *
                    (1 - oi.discount / 100)
                ')
            );

        return [
            'title' => 'Revenue Summary',
            'items' => [
                'Today revenue is '.setting('currency', 'Rp').' '.number_format($todayRevenue, 0, ',', '.').'.',
                'Current month revenue is '.setting('currency', 'Rp').' '.number_format($monthRevenue, 0, ',', '.').'.',
            ],
        ];
    }

    private function defaultAnswer()
    {
        return [
            'title' => 'RetailOps Copilot',
            'items' => [
                'You can ask about priority, risk, fraud, stock, customer, store, or revenue.',
                'Example: "what is today priority?"',
                'Example: "show high risk customers"',
                'Example: "which products need restock?"',
            ],
        ];
    }
}