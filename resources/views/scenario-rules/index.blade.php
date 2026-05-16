@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Scenario Builder
            </h1>

            <p class="text-muted mb-0">
                Build dynamic fraud and operational rules without coding
            </p>
        </div>

        <div class="d-flex gap-2">

            <form
                method="POST"
                action="/scenario-rules/run"
            >
                @csrf

                <button class="btn btn-danger">
                    <i class="bi bi-play-fill"></i>
                    Run Scenarios
                </button>
            </form>

            <a
                href="/scenario-rules/create"
                class="btn btn-primary"
            >
                + Create Rule
            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Rule Name</th>
                            <th>Condition</th>
                            <th>Risk</th>
                            <th>Status</th>
                            <th width="220">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($rules as $rule)

                            <tr>
                                <td class="fw-semibold">
                                    {{ $rule->rule_name }}
                                </td>

                                <td>
                                    IF
                                    <strong>{{ $rule->condition_field }}</strong>
                                    {{ $rule->operator }}
                                    <strong>{{ $rule->condition_value }}</strong>
                                </td>

                                <td>
                                    @if($rule->severity == 'High')
                                        <span class="badge bg-danger">High</span>
                                    @elseif($rule->severity == 'Medium')
                                        <span class="badge bg-warning text-dark">Medium</span>
                                    @else
                                        <span class="badge bg-primary">Low</span>
                                    @endif

                                    <div class="small text-muted mt-1">
                                        {{ $rule->risk_type }}
                                    </div>
                                </td>

                                <td>
                                    @if($rule->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a
                                            href="/scenario-rules/{{ $rule->id }}/edit"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="/scenario-rules/{{ $rule->id }}"
                                            onsubmit="return confirm('Delete this rule?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center text-muted"
                                >
                                    No scenario rules found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $rules->links() }}
            </div>

        </div>

    </div>

</div>

@endsection