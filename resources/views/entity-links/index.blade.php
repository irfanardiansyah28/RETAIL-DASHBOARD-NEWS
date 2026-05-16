@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="fw-bold">
                Entity Link Analysis
            </h1>

            <p class="text-muted mb-0">
                Detect customer relationship patterns
            </p>

        </div>

        <div>

            <span class="badge bg-dark p-2">
                {{ count($results) }} Groups Found
            </span>

        </div>

    </div>


    @forelse($results as $index => $group)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="fw-bold">
                            Linked Group #{{ $index + 1 }}
                        </h5>

                        <div class="text-muted">

                            Shared:

                            <strong>
                                {{ $group['type'] }}
                            </strong>

                            →

                            {{ $group['shared'] }}

                        </div>

                    </div>

                    <div>

                        <span class="badge bg-danger">
                            {{ $group['total'] }} customers
                        </span>

                    </div>

                </div>


                @if(count($group['cities']) > 0)

                    <div class="mb-3">

                        @foreach($group['cities'] as $city)

                            <span class="badge bg-primary me-1">
                                {{ $city }}
                            </span>

                        @endforeach

                    </div>

                @endif


                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>Customer</th>

                                <th>Phone</th>

                                <th>City</th>

                                <th>Risk</th>

                                <th>Score</th>

                                <th width="100">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($group['customers'] as $customer)

                                <tr>

                                    <td>

                                        {{ $customer->first_name }}
                                        {{ $customer->last_name }}

                                    </td>

                                    <td>

                                        {{ $customer->phone }}

                                    </td>

                                    <td>

                                        {{ $customer->city ?? '-' }}

                                    </td>

                                    <td>

                                        @if(($customer->risk_level ?? 'Low') == 'High')

                                            <span class="badge bg-danger">
                                                High
                                            </span>

                                        @elseif(($customer->risk_level ?? 'Low') == 'Medium')

                                            <span class="badge bg-warning text-dark">
                                                Medium
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                {{ $customer->risk_level ?? 'Low' }}
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        {{ $customer->risk_score ?? 0 }}

                                    </td>

                                    <td>

                                        <a
                                            href="/customers/{{ $customer->customer_id }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            View
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @empty

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center text-muted">

                No linked entities detected

            </div>

        </div>

    @endforelse

</div>

@endsection