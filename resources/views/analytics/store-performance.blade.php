@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Store Performance Ranking
            </h1>

            <p class="text-muted mb-0">
                Compare store revenue, orders, and item sales performance
            </p>
        </div>

        <a
            href="/"
            class="btn btn-secondary"
        >
            Back to Dashboard
        </a>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="/analytics/store-performance"
            >

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

                        <button class="btn btn-primary w-100">
                            Filter
                        </button>

                        <a
                            href="/analytics/store-performance"
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
                        Total Revenue
                    </p>

                    <h4 class="fw-bold">
                        {{ setting('currency', 'Rp') }}
                        {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h4>

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
                        {{ $totalOrders }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Items Sold
                    </p>

                    <h3 class="fw-bold">
                        {{ $totalItemsSold }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Top Store
                    </p>

                    <h5 class="fw-bold text-primary">
                        {{ $topStore->store_name ?? '-' }}
                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Revenue by Store
                    </h5>

                    <canvas
                        id="storeRevenueChart"
                        height="150"
                    ></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Orders by Store
                    </h5>

                    <canvas
                        id="storeOrderChart"
                        height="150"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Store Ranking
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Rank</th>
                            <th>Store</th>
                            <th>Orders</th>
                            <th>Items Sold</th>
                            <th>Revenue</th>
                            <th>Performance</th>
                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $maxRevenue = $stores->max('total_revenue') ?: 1;
                        @endphp

                        @forelse($stores as $index => $store)

                            @php
                                $percentage = round(
                                    ($store->total_revenue / $maxRevenue) * 100
                                );
                            @endphp

                            <tr>

                                <td>
                                    @if($index == 0)
                                        🥇
                                    @elseif($index == 1)
                                        🥈
                                    @elseif($index == 2)
                                        🥉
                                    @else
                                        #{{ $index + 1 }}
                                    @endif
                                </td>

                                <td class="fw-semibold">
                                    {{ $store->store_name }}
                                </td>

                                <td>
                                    {{ $store->total_orders }}
                                </td>

                                <td>
                                    {{ $store->total_items_sold }}
                                </td>

                                <td>
                                    {{ setting('currency', 'Rp') }}
                                    {{ number_format($store->total_revenue, 0, ',', '.') }}
                                </td>

                                <td>
                                    <div class="progress" style="height: 16px;">
                                        <div
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width: {{ $percentage }}%;"
                                        >
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="6"
                                    class="text-center text-muted"
                                >
                                    No store performance data found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const storeLabels = @json($storeLabels);

    const revenueData = @json($revenueData);

    const orderData = @json($orderData);

    new Chart(
        document.getElementById('storeRevenueChart'),
        {
            type: 'bar',
            data: {
                labels: storeLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData
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
        document.getElementById('storeOrderChart'),
        {
            type: 'bar',
            data: {
                labels: storeLabels,
                datasets: [{
                    label: 'Orders',
                    data: orderData
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