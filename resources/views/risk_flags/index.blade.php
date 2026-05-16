@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Risk Flag Engine
            </h1>

            <p class="text-muted mb-0">
                Monitor suspicious activities and operational risk indicators
            </p>
        </div>

        <div class="d-flex gap-2">

            <form
                method="POST"
                action="{{ route('risk-flags.scan') }}"
            >
                @csrf

                <button class="btn btn-danger">
                    <i class="bi bi-shield-exclamation"></i>
                    Run Fraud Scan
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('risk-flags.scan-dynamic-pattern') }}"
            >
                @csrf

                <button class="btn btn-dark">
                    <i class="bi bi-search-heart"></i>
                    Dynamic Pattern Scan
                </button>
            </form>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Open Risk</p>
                    <h3 class="fw-bold">{{ $openCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">High Risk</p>
                    <h3 class="fw-bold text-danger">{{ $highCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Medium Risk</p>
                    <h3 class="fw-bold text-warning">{{ $mediumCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Low Risk</p>
                    <h3 class="fw-bold text-primary">{{ $lowCount }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET" action="/risk-flags">

                <div class="row align-items-end">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search risk / module / user..."
                            value="{{ $search }}"
                        >

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-semibold">
                            Severity
                        </label>

                        <select
                            name="severity"
                            class="form-control"
                        >
                            <option value="">All Severity</option>
                            <option value="High" {{ $severity == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ $severity == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ $severity == 'Low' ? 'selected' : '' }}>Low</option>
                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-control"
                        >
                            <option value="">All Status</option>
                            <option value="Open" {{ $status == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Closed" {{ $status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>

                    </div>

                    <div class="col-md-2 mb-3 d-flex gap-2">

                        <button class="btn btn-primary w-100">
                            Filter
                        </button>

                        <a
                            href="/risk-flags"
                            class="btn btn-secondary w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Severity</th>
                            <th>Risk Type</th>
                            <th>Module</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Status</th>
                            <th width="220">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($riskFlags as $flag)

                            <tr>
                                <td>
                                    {{ $flag->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    @if($flag->severity == 'High')
                                        <span class="badge bg-danger">High</span>
                                    @elseif($flag->severity == 'Medium')
                                        <span class="badge bg-warning text-dark">Medium</span>
                                    @else
                                        <span class="badge bg-primary">Low</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $flag->risk_type }}
                                </td>

                                <td>
                                    {{ $flag->module ?? '-' }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        <a
                                            href="/risk-flags/{{ $flag->id }}"
                                            class="text-decoration-none text-dark"
                                        >
                                            {{ $flag->title }}
                                        </a>
                                    </div>

                                    <small class="text-muted">
                                        {{ $flag->description }}
                                    </small>
                                </td>

                                <td>
                                    {{ $flag->user_name ?? 'System' }}
                                </td>

                                <td>
                                    @if($flag->status == 'Open')
                                        <span class="badge bg-danger">Open</span>
                                    @else
                                        <span class="badge bg-success">Closed</span>
                                    @endif
                                </td>

                                <td>

                                    <div class="d-flex gap-2 flex-wrap">

                                        <a
                                            href="/risk-flags/{{ $flag->id }}"
                                            class="btn btn-info btn-sm"
                                        >
                                            Detail
                                        </a>

                                        @if($flag->status == 'Open')

                                            <form
                                                method="POST"
                                                action="/risk-flags/{{ $flag->id }}/close"
                                            >
                                                @csrf

                                                <button class="btn btn-success btn-sm">
                                                    Close
                                                </button>
                                            </form>

                                        @else

                                            <form
                                                method="POST"
                                                action="/risk-flags/{{ $flag->id }}/reopen"
                                            >
                                                @csrf

                                                <button class="btn btn-warning btn-sm">
                                                    Reopen
                                                </button>
                                            </form>

                                        @endif

                                    </div>

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="text-center text-muted"
                                >
                                    No risk flags found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $riskFlags->links() }}
            </div>

        </div>

    </div>

</div>

@endsection