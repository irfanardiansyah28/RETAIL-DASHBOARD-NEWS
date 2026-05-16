@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Inventory Forecast Prediction
            </h1>

            <p class="text-muted mb-0">
                Predict stock availability and suggested restock quantity
            </p>
        </div>

        <a
            href="/stocks"
            class="btn btn-secondary"
        >
            Back to Stocks
        </a>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Critical</p>
                    <h3 class="fw-bold text-danger">{{ $criticalCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Warning</p>
                    <h3 class="fw-bold text-warning">{{ $warningCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Safe</p>
                    <h3 class="fw-bold text-success">{{ $safeCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">No Sales</p>
                    <h3 class="fw-bold text-secondary">{{ $noSalesCount }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Restock Recommendation
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Store</th>
                            <th>Current Stock</th>
                            <th>Sold 30 Days</th>
                            <th>Avg / Day</th>
                            <th>Days Left</th>
                            <th>Suggested Restock</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($forecasts as $item)

                            <tr>
                                <td class="fw-semibold">
                                    {{ $item->product_name }}
                                </td>

                                <td>
                                    {{ $item->store_name }}
                                </td>

                                <td>
                                    {{ $item->current_stock }}
                                </td>

                                <td>
                                    {{ $item->sold_last_30_days }}
                                </td>

                                <td>
                                    {{ $item->avg_daily_sales }}
                                </td>

                                <td>
                                    @if($item->days_left === null)
                                        -
                                    @else
                                        {{ $item->days_left }} days
                                    @endif
                                </td>

                                <td>
                                    @if($item->suggested_restock > 0)
                                        <span class="badge bg-primary">
                                            +{{ $item->suggested_restock }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($item->forecast_status == 'Critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @elseif($item->forecast_status == 'Warning')
                                        <span class="badge bg-warning text-dark">Warning</span>
                                    @elseif($item->forecast_status == 'Safe')
                                        <span class="badge bg-success">Safe</span>
                                    @else
                                        <span class="badge bg-secondary">No Sales</span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="text-center text-muted"
                                >
                                    No inventory forecast data found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="alert alert-info mt-3 mb-0">
                Forecast uses last 30 days sales and target restock coverage of
                <strong>{{ $targetDays }} days</strong>.
            </div>

        </div>

    </div>

</div>

@endsection