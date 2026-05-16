@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Approval Workflow
            </h1>

            <p class="text-muted mb-0">
                Review and approve high-risk operational actions
            </p>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending</p>
                    <h3 class="fw-bold text-warning">{{ $pendingCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Approved</p>
                    <h3 class="fw-bold text-success">{{ $approvedCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Rejected</p>
                    <h3 class="fw-bold text-danger">{{ $rejectedCount }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET" action="/approvals">

                <div class="row align-items-end">

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-control"
                        >
                            <option value="">All Status</option>
                            <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-flex gap-2">

                        <button class="btn btn-primary w-100">
                            Filter
                        </button>

                        <a
                            href="/approvals"
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
                            <th>Type</th>
                            <th>Title</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Decision By</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($approvalRequests as $approval)

                            <tr>
                                <td>
                                    {{ $approval->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    {{ $approval->request_type }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $approval->title }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $approval->description }}
                                    </small>
                                </td>

                                <td>
                                    {{ $approval->requested_by_name ?? 'System' }}
                                </td>

                                <td>
                                    @if($approval->status == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($approval->status == 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $approval->approved_by_name ?? '-' }}
                                </td>

                                <td>
                                    @if($approval->status == 'Pending')

                                        <div class="d-flex gap-2">

                                            <form
                                                method="POST"
                                                action="/approvals/{{ $approval->id }}/approve"
                                            >
                                                @csrf

                                                <button class="btn btn-success btn-sm">
                                                    Approve
                                                </button>
                                            </form>

                                            <form
                                                method="POST"
                                                action="/approvals/{{ $approval->id }}/reject"
                                            >
                                                @csrf

                                                <button class="btn btn-danger btn-sm">
                                                    Reject
                                                </button>
                                            </form>

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Completed
                                        </span>

                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center text-muted"
                                >
                                    No approval request found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $approvalRequests->links() }}
            </div>

        </div>

    </div>

</div>

@endsection