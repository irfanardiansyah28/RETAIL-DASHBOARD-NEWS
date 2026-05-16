@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Order Heatmap Analytics
            </h1>

            <p class="text-muted mb-0">
                Analyze peak order days, hours, and revenue behavior
            </p>
        </div>

        <a
            href="/orders"
            class="btn btn-secondary"
        >
            Back to Orders
        </a>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="/analytics/order-heatmap"
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
                            href="/analytics/order-heatmap"
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
                        Peak Day
                    </p>

                    <h4 class="fw-bold text-primary">
                        {{ $peakDay->day_name ?? '-' }}
                    </h4>

                    <small class="text-muted">
                        {{ $peakDay->total_orders ?? 0 }} orders
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Peak Hour
                    </p>

                    <h4 class="fw-bold text-success">
                        @if($peakHour)
                            {{ str_pad($peakHour->hour, 2, '0', STR_PAD_LEFT) }}:00
                        @else
                            -
                        @endif
                    </h4>

                    <small class="text-muted">
                        {{ $peakHour->total_orders ?? 0 }} orders
                    </small>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Orders by Day
                    </h5>

                    <canvas
                        id="ordersByDayChart"
                        height="150"
                    ></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Orders by Hour
                    </h5>

                    <canvas
                        id="ordersByHourChart"
                        height="150"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-7 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Revenue by Day
                    </h5>

                    <canvas
                        id="revenueByDayChart"
                        height="140"
                    ></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-5 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Heatmap Insight
                    </h5>

                    <div class="alert alert-primary">
                        Peak order day is
                        <strong>{{ $peakDay->day_name ?? '-' }}</strong>
                        with
                        <strong>{{ $peakDay->total_orders ?? 0 }}</strong>
                        orders.
                    </div>

                    <div class="alert alert-success">
                        Peak order hour is
                        <strong>
                            @if($peakHour)
                                {{ str_pad($peakHour->hour, 2, '0', STR_PAD_LEFT) }}:00
                            @else
                                -
                            @endif
                        </strong>
                        with
                        <strong>{{ $peakHour->total_orders ?? 0 }}</strong>
                        orders.
                    </div>

                    <div class="alert alert-warning mb-0">
                        Use this insight for staffing, campaign timing,
                        and operational planning.
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Order Heatmap Table
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Day</th>
                            <th>Total Orders</th>
                            <th>Intensity</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $maxOrders = $ordersByDay->max('total_orders') ?: 1;
                        @endphp

                        @forelse($ordersByDay as $day)

                            @php
                                $percentage = round(
                                    ($day->total_orders / $maxOrders) * 100
                                );
                            @endphp

                            <tr>
                                <td>
                                    {{ $day->day_name }}
                                </td>

                                <td>
                                    {{ $day->total_orders }}
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
                                    colspan="3"
                                    class="text-center text-muted"
                                >
                                    No order heatmap data found
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

    const dayLabels = @json($dayLabels);

    const dayData = @json($dayData);

    const hourLabels = @json($hourLabels);

    const hourData = @json($hourData);

    const revenueDayLabels = @json($revenueDayLabels);

    const revenueDayData = @json($revenueDayData);

    new Chart(
        document.getElementById('ordersByDayChart'),
        {
            type: 'bar',
            data: {
                labels: dayLabels,
                datasets: [{
                    label: 'Orders',
                    data: dayData
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
        document.getElementById('ordersByHourChart'),
        {
            type: 'line',
            data: {
                labels: hourLabels,
                datasets: [{
                    label: 'Orders',
                    data: hourData,
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
        document.getElementById('revenueByDayChart'),
        {
            type: 'bar',
            data: {
                labels: revenueDayLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueDayData
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