@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Dashboard
            </h1>

            <p class="text-muted mb-0">
                Retail Management Analytics Overview
            </p>
        </div>

        <div>
            <span class="badge bg-dark p-2">
                Pending Orders: {{ $pendingOrders }}
            </span>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET" action="/">

                <div class="row align-items-end">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ $startDate }}"
                        >

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ $endDate }}"
                        >

                    </div>

                    <div class="col-md-4 mb-3 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="bi bi-funnel"></i>
                            Filter
                        </button>

                        <a
                            href="/"
                            class="btn btn-secondary w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Today's Revenue
                    </p>

                    <h4 class="fw-bold mb-1">
                        {{ setting('currency', 'Rp') }}
                        {{ number_format($todayRevenue, 0, ',', '.') }}
                    </h4>

                    @include(
                        'partials.kpi-trend',
                        [
                            'trend' => $todayRevenueTrend,
                            'text' => 'vs yesterday'
                        ]
                    )

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Weekly Revenue
                    </p>

                    <h4 class="fw-bold mb-1">
                        {{ setting('currency', 'Rp') }}
                        {{ number_format($weeklyRevenue, 0, ',', '.') }}
                    </h4>

                    @include(
                        'partials.kpi-trend',
                        [
                            'trend' => $weeklyRevenueTrend,
                            'text' => 'vs last week'
                        ]
                    )

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Monthly Revenue
                    </p>

                    <h4 class="fw-bold mb-1">
                        {{ setting('currency', 'Rp') }}
                        {{ number_format($currentMonthRevenue, 0, ',', '.') }}
                    </h4>

                    @include(
                        'partials.kpi-trend',
                        [
                            'trend' => $monthlyRevenueTrend,
                            'text' => 'vs last month'
                        ]
                    )

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Completed Orders
                    </p>

                    <h4 class="fw-bold">
                        {{ $completedOrders }}
                    </h4>

                    <small class="text-muted fw-semibold">
                        <i class="bi bi-check-circle"></i>
                        Total completed sales
                    </small>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Open Risk Flags
                    </p>

                    <h3 class="fw-bold">
                        {{ $openRiskFlags }}
                    </h3>

                    <a
                        href="/risk-flags"
                        class="btn btn-sm btn-outline-dark mt-2"
                    >
                        View Risks
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        High Risk
                    </p>

                    <h3 class="fw-bold text-danger">
                        {{ $highRiskFlags }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Medium Risk
                    </p>

                    <h3 class="fw-bold text-warning">
                        {{ $mediumRiskFlags }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Low Risk
                    </p>

                    <h3 class="fw-bold text-primary">
                        {{ $lowRiskFlags }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">

            <div
                class="card border-0 shadow-lg text-white h-100"
                style="background: linear-gradient(135deg,#2563eb,#1d4ed8); border-radius:20px;"
            >

                <div class="card-body">

                    <h6 class="text-uppercase">
                        Total Products
                    </h6>

                    <h2 class="fw-bold">
                        {{ $totalProducts }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div
                class="card border-0 shadow-lg text-white h-100"
                style="background: linear-gradient(135deg,#059669,#047857); border-radius:20px;"
            >

                <div class="card-body">

                    <h6 class="text-uppercase">
                        Total Orders
                    </h6>

                    <h2 class="fw-bold">
                        {{ $totalOrders }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div
                class="card border-0 shadow-lg text-white h-100"
                style="background: linear-gradient(135deg,#7c3aed,#6d28d9); border-radius:20px;"
            >

                <div class="card-body">

                    <h6 class="text-uppercase">
                        Total Customers
                    </h6>

                    <h2 class="fw-bold">
                        {{ $totalCustomers }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div
                class="card border-0 shadow-lg text-white h-100"
                style="background: linear-gradient(135deg,#ea580c,#c2410c); border-radius:20px;"
            >

                <div class="card-body">

                    <h6 class="text-uppercase">
                        Total Revenue
                    </h6>

                    <h3 class="fw-bold">
                        {{ setting('currency', 'Rp') }}
                        {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-8 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Revenue Last 7 Days
                    </h5>

                    <canvas id="revenueChart" height="120"></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Order Status Summary
                    </h5>

                    <canvas id="orderStatusChart" height="180"></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Top Selling Products
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total Sold</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($topSellingProducts as $product)

                                    <tr>
                                        <td>{{ $product->product_name }}</td>

                                        <td>{{ $product->total_sold }}</td>

                                        <td>
                                            {{ setting('currency', 'Rp') }}
                                            {{ number_format($product->total_revenue, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="3"
                                            class="text-center text-muted"
                                        >
                                            No sales data available
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="mb-4">
                        Monthly Orders Analytics
                    </h5>

                    <canvas
                        id="monthlyOrdersChart"
                        height="140"
                    ></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="mb-4">
                        Monthly Revenue Analytics
                    </h5>

                    <canvas
                        id="monthlyRevenueChart"
                        height="140"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-4 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="mb-4">
                        Top Selling Products Chart
                    </h5>

                    <canvas
                        id="topProductsChart"
                        height="240"
                    ></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="mb-4 text-danger">

                        ⚠ Low Stock Warning

                        <small class="text-muted d-block mt-1">
                            Threshold: ≤ {{ $lowStockLimit }}
                        </small>

                    </h5>

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Store</th>
                                <th>Stock</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($lowStocks as $stock)

                                <tr>
                                    <td>{{ $stock->product_name }}</td>

                                    <td>{{ $stock->store_name }}</td>

                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $stock->quantity }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="3"
                                        class="text-center text-muted"
                                    >
                                        No low stock products
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                    <div class="mt-3">

                        <a
                            href="/stocks"
                            class="btn btn-outline-danger btn-sm"
                        >
                            View Stock Management
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="mb-4">
                        Best Customers
                    </h5>

                    <table class="table table-hover">

                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($bestCustomers as $customer)

                                <tr>
                                    <td>{{ $customer->customer_name }}</td>

                                    <td>
                                        {{ setting('currency', 'Rp') }}
                                        {{ number_format($customer->total_spent, 0, ',', '.') }}
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="2"
                                        class="text-center text-muted"
                                    >
                                        No customer data available
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- SMART RECOMMENDATIONS -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">
                    🧠 Smart Recommendations
                </h5>

                <span class="badge bg-dark">
                    {{ count($smartInsights ?? []) }}
                    Insights
                </span>

            </div>

            @if(count($smartInsights ?? []) > 0)

                <div class="row">

                    @foreach($smartInsights as $item)

                        <div class="col-md-6 mb-3">

                            <div class="alert alert-{{ $item['type'] }} mb-0">

                                <div class="fw-semibold">

                                    {{ $item['icon'] }}
                                    {{ $item['text'] }}

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-muted">
                    No recommendations available
                </div>

            @endif

        </div>

    </div>

    <!-- RECENT ORDERS -->
    
    <<div class="card border-0 shadow-sm mb-4">

<div class="card-body">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h4 class="fw-bold">
                Executive Summary
            </h4>

            <p class="text-muted mb-0">
                AI-powered business health overview
            </p>

        </div>

        <div>

            @if($healthScore >= 80)

                <span class="badge bg-success p-3">
                    {{ $businessStatus }}
                </span>

            @elseif($healthScore >= 60)

                <span class="badge bg-primary p-3">
                    {{ $businessStatus }}
                </span>

            @elseif($healthScore >= 40)

                <span class="badge bg-warning text-dark p-3">
                    {{ $businessStatus }}
                </span>

            @else

                <span class="badge bg-danger p-3">
                    {{ $businessStatus }}
                </span>

            @endif

        </div>

    </div>


    <div class="row mb-4">

        <div class="col-md-3">

            <div class="text-muted">
                Business Health Score
            </div>

            <h1 class="fw-bold">
                {{ $healthScore }}/100
            </h1>

        </div>

        <div class="col-md-9">

            <div
                class="progress"
                style="
                    height:25px;
                    border-radius:20px;
                "
            >

                <div
                    class="progress-bar"
                    role="progressbar"
                    style="
                        width: {{ $healthScore }}%;
                        font-weight:bold;
                    "
                >

                    {{ $healthScore }}%

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        @foreach($executiveInsights as $item)

            <div class="col-md-6 mb-3">

                <div
                    class="border rounded p-3 h-100"
                >

                    <div class="fw-semibold">

                        {{ $item['icon'] }}

                        {{ $item['text'] }}

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

</div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="mb-4">
                Recent Orders
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($recentOrders as $order)

                            <tr>
                                <td>
                                    #{{ $order->order_id }}
                                </td>

                                <td>
                                    {{ $order->customer_name }}
                                </td>

                                <td>
                                    {{ $order->order_date }}
                                </td>

                                <td>
                                    {{ setting('currency', 'Rp') }}
                                    {{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if($order->status == 'Pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @elseif($order->status == 'Paid')

                                        <span class="badge bg-primary">
                                            Paid
                                        </span>

                                    @elseif($order->status == 'Shipped')

                                        <span class="badge bg-info text-dark">
                                            Shipped
                                        </span>

                                    @elseif($order->status == 'Completed')

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                    @elseif($order->status == 'Cancelled')

                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $order->status }}
                                        </span>

                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center text-muted"
                                >
                                    No recent orders available
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const monthlyOrderLabels = @json(
        $monthlyOrders->pluck('month')
    );

    const monthlyOrderData = @json(
        $monthlyOrders->pluck('total')
    );

    const monthlyRevenueLabels = @json(
        $monthlyRevenue->pluck('month')
    );

    const monthlyRevenueData = @json(
        $monthlyRevenue->pluck('revenue')
    );

    const topProductLabels = @json(
        $topProducts->pluck('product_name')
    );

    const topProductData = @json(
        $topProducts->pluck('total_sold')
    );

    const lastSevenDaysRevenueLabels = @json(
        $revenueChartLabels
    );

    const lastSevenDaysRevenueData = @json(
        $revenueChartData
    );

    const orderStatusLabels = @json(
        $orderStatusLabels
    );

    const orderStatusData = @json(
        $orderStatusData
    );

    new Chart(
        document.getElementById('monthlyOrdersChart'),
        {
            type: 'bar',
            data: {
                labels: monthlyOrderLabels,
                datasets: [{
                    label: 'Orders',
                    data: monthlyOrderData
                }]
            },
            options: {
                responsive: true
            }
        }
    );

    new Chart(
        document.getElementById('monthlyRevenueChart'),
        {
            type: 'line',
            data: {
                labels: monthlyRevenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: monthlyRevenueData,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true
            }
        }
    );

    new Chart(
        document.getElementById('topProductsChart'),
        {
            type: 'doughnut',
            data: {
                labels: topProductLabels,
                datasets: [{
                    data: topProductData
                }]
            },
            options: {
                responsive: true
            }
        }
    );

    new Chart(
        document.getElementById('revenueChart'),
        {
            type: 'line',
            data: {
                labels: lastSevenDaysRevenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: lastSevenDaysRevenueData,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        }
    );

    new Chart(
        document.getElementById('orderStatusChart'),
        {
            type: 'doughnut',
            data: {
                labels: orderStatusLabels,
                datasets: [{
                    data: orderStatusData
                }]
            },
            options: {
                responsive: true
            }
        }
    );

});

</script>

<div
    class="card border-0 shadow-sm mt-4"
    id="retailCopilotPanel"
>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h5 class="fw-bold mb-1">
                    🤖 RetailOps Copilot
                </h5>

                <p class="text-muted mb-0">
                    Ask about priority, risk, stock, customer, store, or revenue
                </p>
            </div>

            <span class="badge bg-dark">
                Local AI
            </span>

        </div>

        <form id="copilotForm">

            @csrf

            <div class="input-group mb-3">

                <input
                    type="text"
                    id="copilotQuestion"
                    class="form-control"
                    placeholder="Example: what is today priority?"
                    autocomplete="off"
                >

                <button
                    class="btn btn-primary"
                    type="submit"
                >
                    Ask
                </button>

            </div>

        </form>

        <div
            id="copilotAnswer"
            class="border rounded p-3 bg-light"
        >

            <div class="fw-bold mb-2">
                RetailOps Copilot
            </div>

            <div class="text-muted">
                Ask me about business priority, fraud risk, stock, customer, store, or revenue.
            </div>

        </div>

        <div class="mt-3 d-flex flex-wrap gap-2">

            <button
                type="button"
                class="btn btn-outline-dark btn-sm copilot-suggestion"
                data-question="what is today priority?"
            >
                Today Priority
            </button>

            <button
                type="button"
                class="btn btn-outline-danger btn-sm copilot-suggestion"
                data-question="show fraud risk"
            >
                Fraud Risk
            </button>

            <button
                type="button"
                class="btn btn-outline-warning btn-sm copilot-suggestion"
                data-question="which products need restock?"
            >
                Restock
            </button>

            <button
                type="button"
                class="btn btn-outline-primary btn-sm copilot-suggestion"
                data-question="show high risk customers"
            >
                High Risk Customers
            </button>

            <button
                type="button"
                class="btn btn-outline-success btn-sm copilot-suggestion"
                data-question="show store performance"
            >
                Store Performance
            </button>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const copilotForm = document.getElementById('copilotForm');

    const copilotQuestion = document.getElementById('copilotQuestion');

    const copilotAnswer = document.getElementById('copilotAnswer');

    const suggestionButtons = document.querySelectorAll('.copilot-suggestion');

    function askCopilot(question) {

        if (!question || question.trim() === '') {
            return;
        }

        copilotAnswer.innerHTML = `
            <div class="fw-bold mb-2">
                RetailOps Copilot
            </div>

            <div class="text-muted">
                Thinking...
            </div>
        `;

        fetch(
            "{{ route('retail-copilot.ask') }}",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    question: question
                })
            }
        )

        .then(response => response.json())

        .then(data => {

            let html = `
                <div class="fw-bold mb-3">
                    ${data.title}
                </div>

                <ol class="mb-0">
            `;

            data.items.forEach(item => {

                html += `
                    <li class="mb-2">
                        ${item}
                    </li>
                `;

            });

            html += `
                </ol>
            `;

            copilotAnswer.innerHTML = html;

        })

        .catch(() => {

            copilotAnswer.innerHTML = `
                <div class="text-danger fw-bold">
                    Failed to get copilot response.
                </div>
            `;

        });

    }

    if (copilotForm) {

        copilotForm.addEventListener('submit', function (e) {

            e.preventDefault();

            askCopilot(
                copilotQuestion.value
            );

        });

    }

    suggestionButtons.forEach(button => {

        button.addEventListener('click', function () {

            const question = this.getAttribute('data-question');

            copilotQuestion.value = question;

            askCopilot(question);

        });

    });

});

</script>
@endsection