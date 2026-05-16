@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Audit Dashboard
            </h1>

            <p class="text-muted mb-0">
                Monitor user activity, stock changes, and high-risk actions
            </p>
        </div>

        <a
            href="/activity-logs"
            class="btn btn-dark"
        >
            View Full Logs
        </a>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="/audit-dashboard"
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

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filter
                        </button>

                        <a
                            href="/audit-dashboard"
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

        <div class="col-md-2 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Activities
                    </p>

                    <h3 class="fw-bold">
                        {{ $totalActivities }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Today
                    </p>

                    <h3 class="fw-bold">
                        {{ $todayActivities }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Stock Updates
                    </p>

                    <h3 class="fw-bold text-primary">
                        {{ $stockUpdates }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Order Activity
                    </p>

                    <h3 class="fw-bold text-success">
                        {{ $orderActivities }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Delete Action
                    </p>

                    <h3 class="fw-bold text-danger">
                        {{ $deleteActivities }}
                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-2 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Settings Change
                    </p>

                    <h3 class="fw-bold text-warning">
                        {{ $settingsChanges }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Activity by Module
                    </h5>

                    <canvas
                        id="moduleChart"
                        height="150"
                    ></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Top Actions
                    </h5>

                    <canvas
                        id="actionChart"
                        height="150"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-5 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Most Active Users
                    </h5>

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>User</th>
                                <th>Total Activity</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($mostActiveUsers as $user)

                                <tr>
                                    <td>
                                        {{ $user->user_name ?? 'System' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $user->total }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="2"
                                        class="text-center text-muted"
                                    >
                                        No activity found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-7 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3 text-danger">
                        High-Risk Activities
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($highRiskActivities as $activity)

                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}
                                        </td>

                                        <td>
                                            {{ $activity->user_name ?? 'System' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-danger">
                                                {{ $activity->action }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $activity->description ?? $activity->activity ?? '-' }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="text-center text-muted"
                                        >
                                            No high-risk activity found
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

    <div class="row mb-4">

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Recent Activities
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Module</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($recentActivities as $activity)

                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}
                                        </td>

                                        <td>
                                            {{ $activity->user_name ?? 'System' }}
                                        </td>

                                        <td>
                                            {{ $activity->module ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $activity->action ?? $activity->activity ?? '-' }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="4"
                                            class="text-center text-muted"
                                        >
                                            No recent activity found
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Latest Stock Movements
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>Time</th>
                                    <th>Product</th>
                                    <th>Old</th>
                                    <th>New</th>
                                    <th>Diff</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($latestStockMovements as $movement)

                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}
                                        </td>

                                        <td>
                                            {{ $movement->product_name }}
                                        </td>

                                        <td>
                                            {{ $movement->old_quantity }}
                                        </td>

                                        <td>
                                            {{ $movement->new_quantity }}
                                        </td>

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
                                            colspan="5"
                                            class="text-center text-muted"
                                        >
                                            No stock movement found
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

    const moduleLabels = @json($moduleLabels);

    const moduleData = @json($moduleData);

    const actionLabels = @json($actionLabels);

    const actionData = @json($actionData);

    new Chart(
        document.getElementById('moduleChart'),
        {
            type: 'doughnut',
            data: {
                labels: moduleLabels,
                datasets: [{
                    data: moduleData
                }]
            },
            options: {
                responsive: true
            }
        }
    );

    new Chart(
        document.getElementById('actionChart'),
        {
            type: 'bar',
            data: {
                labels: actionLabels,
                datasets: [{
                    label: 'Total',
                    data: actionData
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