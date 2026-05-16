@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Customer Profile
            </h1>

            <p class="text-muted mb-0">
                Customer 360 profile, order history, spending summary, and risk insight
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="/customers"
                class="btn btn-secondary"
            >
                Back
            </a>

            <a
                href="/customers/{{ $customer->customer_id }}/edit"
                class="btn btn-warning"
            >
                Edit Customer
            </a>

        </div>

        <div class="col-md-3 mb-3">

    <small class="text-muted">
        Auto Risk Score
    </small>

    <h4 class="fw-bold">
        {{ $customer->risk_score ?? 0 }}/100
    </h4>

    @if(($customer->risk_level ?? 'Low') == 'High')

        <span class="badge bg-danger">
            High Risk
        </span>

    @elseif(($customer->risk_level ?? 'Low') == 'Medium')

        <span class="badge bg-warning text-dark">
            Medium Risk
        </span>

    @else

        <span class="badge bg-success">
            Low Risk
        </span>

    @endif

</div>

    </div>

    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Customer Information
                    </h5>

                    <div class="mb-3">
                        <small class="text-muted">
                            Full Name
                        </small>

                        <div class="fw-semibold">
                            {{ $customer->first_name }}
                            {{ $customer->last_name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            Email
                        </small>

                        <div>
                            @if(!empty($customer->email))
                                {{ $customer->email }}
                            @else
                                <span class="badge bg-danger">
                                    Missing Email
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            Phone
                        </small>

                        <div>
                            @if(!empty($customer->phone))
                                {{ $customer->phone }}
                            @else
                                <span class="badge bg-danger">
                                    Missing Phone
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            Address
                        </small>

                        <div>
                            @if(
                                !empty($customer->street) ||
                                !empty($customer->city) ||
                                !empty($customer->state) ||
                                !empty($customer->zip_code)
                            )

                                {{ $customer->street ?? '-' }}
                                <br>
                                {{ $customer->city ?? '' }}
                                {{ $customer->state ?? '' }}
                                {{ $customer->zip_code ?? '' }}

                            @else

                                <span class="badge bg-danger">
                                    Missing Address
                                </span>

                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            Segment
                        </small>

                        <div>
                            @if(($customer->segment ?? 'New') == 'VIP')
                                <span class="badge bg-warning text-dark">VIP</span>
                            @elseif(($customer->segment ?? 'New') == 'Regular')
                                <span class="badge bg-success">Regular</span>
                            @elseif(($customer->segment ?? 'New') == 'Dormant')
                                <span class="badge bg-secondary">Dormant</span>
                            @elseif(($customer->segment ?? 'New') == 'High Risk')
                                <span class="badge bg-danger">High Risk</span>
                            @else
                                <span class="badge bg-primary">New</span>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-8 mb-3">

            <div class="row">

                <div class="col-md-4 mb-3">
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

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">
                                Total Spent
                            </p>

                            <h3 class="fw-bold">
                                {{ setting('currency', 'Rp') }}
                                {{ number_format($totalSpent, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">
                                Last Order
                            </p>

                            <h5 class="fw-bold">
                                {{ $lastOrderDate ?? '-' }}
                            </h5>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <h5 class="fw-bold mb-1">
                                Customer Profile Completion
                            </h5>

                            <small class="text-muted">
                                Fill missing customer information before processing future orders
                            </small>
                        </div>

                        <div>
                            <span class="badge bg-primary p-2">
                                {{ $profilePercentage }}%
                            </span>
                        </div>

                    </div>

                    <div
                        class="progress mt-3"
                        style="height: 10px;"
                    >
                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $profilePercentage }}%;"
                            aria-valuenow="{{ $profilePercentage }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>
                    </div>

                    @if(count($missingFields) > 0)

                        <div class="mt-4">

                            <h6 class="text-danger mb-2">
                                Missing Information
                            </h6>

                            <div class="d-flex flex-wrap gap-2">

                                @foreach($missingFields as $field)

                                    <span class="badge bg-danger p-2">
                                        {{ $field }}
                                    </span>

                                @endforeach

                            </div>

                            <div class="alert alert-warning mt-3 mb-0">

                                Please complete the missing fields above:
                                <strong>
                                    {{ implode(', ', $missingFields) }}
                                </strong>

                            </div>

                            <div class="mt-3">

                                <a
                                    href="/customers/{{ $customer->customer_id }}/edit"
                                    class="btn btn-warning"
                                >
                                    Complete Customer Data
                                </a>

                            </div>

                        </div>

                    @else

                        <div class="alert alert-success mt-4 mb-0">
                            Customer profile is complete
                            <strong>✓</strong>
                        </div>

                    @endif

                </div>

            </div>

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Order Status Summary
                    </h5>

                    <div class="d-flex flex-wrap gap-2">

                        @forelse($statusSummary as $status)

                            @php
                                $badgeClass = 'bg-secondary';

                                if ($status->status == 'Pending') {
                                    $badgeClass = 'bg-warning text-dark';
                                } elseif ($status->status == 'Paid') {
                                    $badgeClass = 'bg-primary';
                                } elseif ($status->status == 'Shipped') {
                                    $badgeClass = 'bg-info text-dark';
                                } elseif ($status->status == 'Completed') {
                                    $badgeClass = 'bg-success';
                                } elseif ($status->status == 'Cancelled') {
                                    $badgeClass = 'bg-danger';
                                }
                            @endphp

                            <span class="badge {{ $badgeClass }} p-2">
                                {{ $status->status }}:
                                {{ $status->total }}
                            </span>

                        @empty

                            <span class="text-muted">
                                No order status available
                            </span>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Customer 360 Intelligence
            </h5>

            <div class="row">

            <div class="col-md-4 mb-3">

<div class="card border-0 shadow-sm h-100">

<div class="card-body">

<p class="text-muted mb-1">

Risk Score

</p>

@if(
$customer->risk_score>=60
)

<span class="badge bg-danger">

HIGH RISK

</span>

@elseif(
$customer->risk_score>=30
)

<span class="badge bg-warning text-dark">

MEDIUM

</span>

@else

<span class="badge bg-success">

LOW

</span>

@endif

<h2 class="fw-bold mt-2">

{{ $customer->risk_score }}

</h2>

</div>

</div>

</div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Customer Score
                    </small>

                    <h4 class="fw-bold">
                        {{ $customerScore }}/100
                    </h4>

                    <div class="progress" style="height: 10px;">
                        <div
                            class="progress-bar"
                            style="width: {{ $customerScore }}%;"
                        ></div>
                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Average Order Value
                    </small>

                    <h5 class="fw-bold">
                        {{ setting('currency', 'Rp') }}
                        {{ number_format($averageOrderValue, 0, ',', '.') }}
                    </h5>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Cancel Rate
                    </small>

                    <h5 class="fw-bold {{ $cancelRate >= 30 ? 'text-danger' : 'text-success' }}">
                        {{ $cancelRate }}%
                    </h5>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Cancelled Orders
                    </small>

                    <h5 class="fw-bold">
                        {{ $cancelledOrders }}
                    </h5>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Favorite Product
                    </small>

                    <h6 class="fw-bold">
                        {{ $favoriteProduct->product_name ?? '-' }}
                    </h6>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Open Risk Flags
                    </small>

                    <h5 class="fw-bold {{ $openRiskCount > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $openRiskCount }}
                    </h5>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Segment
                    </small>

                    <div>
                        @if(($customer->segment ?? 'New') == 'VIP')
                            <span class="badge bg-warning text-dark">VIP</span>
                        @elseif(($customer->segment ?? 'New') == 'Regular')
                            <span class="badge bg-success">Regular</span>
                        @elseif(($customer->segment ?? 'New') == 'Dormant')
                            <span class="badge bg-secondary">Dormant</span>
                        @elseif(($customer->segment ?? 'New') == 'High Risk')
                            <span class="badge bg-danger">High Risk</span>
                        @else
                            <span class="badge bg-primary">New</span>
                        @endif
                    </div>

                </div>

                <div class="col-md-3 mb-3">

                    <small class="text-muted">
                        Last Order
                    </small>

                    <h6 class="fw-bold">
                        {{ $lastOrderDate ?? '-' }}
                    </h6>

                </div>

            </div>

            <hr>

            <h6 class="fw-bold mb-3">
                Customer Risk Flags
            </h6>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Severity</th>
                            <th>Risk Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($riskFlags as $flag)

                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($flag->created_at)->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    @if($flag->severity == 'High')
                                        <span class="badge bg-danger">
                                            High
                                        </span>
                                    @elseif($flag->severity == 'Medium')
                                        <span class="badge bg-warning text-dark">
                                            Medium
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            Low
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $flag->risk_type }}
                                </td>

                                <td>
                                    @if($flag->status == 'Open')
                                        <span class="badge bg-danger">
                                            Open
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Closed
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
                                    No customer risk flags found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Order History
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($orders as $order)

                            <tr>

                                <td>
                                    #{{ $order->order_id }}
                                </td>

                                <td>
                                    {{ $order->order_date }}
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

                                <td>
                                    {{ setting('currency', 'Rp') }}
                                    {{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                <td>
                                    <a
                                        href="/orders/{{ $order->order_id }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        View Order
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center text-muted"
                                >
                                    No orders found for this customer
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection