@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Stock Movement History
            </h1>

            <p class="text-muted mb-0">
                Track every stock update and quantity change
            </p>
        </div>

        <a
            href="/stocks"
            class="btn btn-secondary"
        >
            Back to Stocks
        </a>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="/stock-movements"
            >

                <div class="row align-items-end">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search product / store / user"
                            value="{{ $search }}"
                        >

                    </div>

                    <div class="col-md-3 mb-3">

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

                    <div class="col-md-3 mb-3">

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

                    <div class="col-md-2 mb-3 d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filter
                        </button>

                        <a
                            href="/stock-movements"
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
                            <th>Product</th>
                            <th>Store</th>
                            <th>Old Qty</th>
                            <th>New Qty</th>
                            <th>Difference</th>
                            <th>Updated By</th>
                            <th>Notes</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($movements as $movement)

                            <tr>

                                <td>
                                    {{ $movement->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td>
                                    {{ $movement->product_name }}
                                </td>

                                <td>
                                    {{ $movement->store_name }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $movement->old_quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $movement->new_quantity }}
                                    </span>
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

                                <td>
                                    {{ $movement->user_name ?? 'System' }}
                                </td>

                                <td>
                                    {{ $movement->notes ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="text-center text-muted"
                                >
                                    No stock movement history found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $movements->links() }}
            </div>

        </div>

    </div>

</div>

@endsection