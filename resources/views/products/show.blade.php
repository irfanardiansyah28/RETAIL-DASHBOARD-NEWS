@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Product Detail
            </h1>

            <p class="text-muted mb-0">
                Product information, stock, sales, and movement history
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="/products"
                class="btn btn-secondary"
            >
                Back
            </a>

            <a
                href="/products/{{ $product->product_id }}/edit"
                class="btn btn-warning"
            >
                Edit Product
            </a>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h4 class="fw-bold mb-3">
                        {{ $product->product_name }}
                    </h4>

                    <div class="mb-3">
                        <small class="text-muted">Brand</small>
                        <div class="fw-semibold">
                            {{ $product->brand_name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Category</small>
                        <div class="fw-semibold">
                            {{ $product->category_name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Model Year</small>
                        <div class="fw-semibold">
                            {{ $product->model_year }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Price</small>
                        <div class="fw-bold">
                            {{ setting('currency', 'Rp') }}
                            {{ number_format($product->list_price, 0, ',', '.') }}
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-8 mb-3">

            <div class="row">

                <div class="col-md-3 mb-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Total Stock
                            </p>

                            <h3 class="fw-bold">
                                {{ $totalStock }}
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Total Sold
                            </p>

                            <h3 class="fw-bold">
                                {{ $salesSummary->total_sold ?? 0 }}
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Total Orders
                            </p>

                            <h3 class="fw-bold">
                                {{ $salesSummary->total_orders ?? 0 }}
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-muted mb-1">
                                Revenue
                            </p>

                            <h5 class="fw-bold">
                                {{ setting('currency', 'Rp') }}
                                {{ number_format($salesSummary->total_revenue ?? 0, 0, ',', '.') }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Sales Last 30 Days
                    </h5>

                    <canvas
                        id="productSalesChart"
                        height="100"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Stock by Store
                    </h5>

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Store</th>
                                <th>Quantity</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($stockByStore as $stock)

                                <tr>
                                    <td>{{ $stock->store_name }}</td>

                                    <td>{{ $stock->quantity }}</td>

                                    <td>
                                        @if($stock->quantity <= setting('low_stock_threshold', 10))

                                            <span class="badge bg-danger">
                                                Low Stock
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Available
                                            </span>

                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="3"
                                        class="text-center text-muted"
                                    >
                                        No stock data available
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Top Customers
                    </h5>

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($topCustomers as $customer)

                                <tr>
                                    <td>{{ $customer->customer_name }}</td>

                                    <td>{{ $customer->total_qty }}</td>

                                    <td>
                                        {{ setting('currency', 'Rp') }}
                                        {{ number_format($customer->total_spent, 0, ',', '.') }}
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="3"
                                        class="text-center text-muted"
                                    >
                                        No customer sales data
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-7 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Recent Orders
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($recentOrders as $order)

                                    <tr>
                                        <td>
                                            <a href="/orders/{{ $order->order_id }}">
                                                #{{ $order->order_id }}
                                            </a>
                                        </td>

                                        <td>{{ $order->order_date }}</td>

                                        <td>{{ $order->customer_name }}</td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $order->status }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ setting('currency', 'Rp') }}
                                            {{ number_format($order->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="5"
                                            class="text-center text-muted"
                                        >
                                            No recent order data
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-5 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Stock Movement History
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Old</th>
                                    <th>New</th>
                                    <th>Diff</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($stockMovements as $movement)

                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}
                                        </td>

                                        <td>{{ $movement->old_quantity }}</td>

                                        <td>{{ $movement->new_quantity }}</td>

                                        <td>
                                            @if($movement->difference > 0)

                                                <span class="badge bg-success">
                                                    +{{ $movement->difference }}
                                                </span>

                                            @elseif($movement->difference < 0)

                                                <span class="badge bg-danger">
                                                    {{ $movement->difference }}
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    0
                                                </span>

                                            @endif
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="text-center text-muted"
                                        >
                                            No movement history
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

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const salesLabels = @json($salesChartLabels);

    const salesData = @json($salesChartData);

    new Chart(
        document.getElementById('productSalesChart'),
        {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Qty Sold',
                    data: salesData,
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

});

</script>

@endsection