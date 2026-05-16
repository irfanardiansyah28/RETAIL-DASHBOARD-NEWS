@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="mb-0">
                Stock Management
            </h1>

            <small class="text-muted">
                Manage stock quantities and track stock movement history
            </small>

        </div>

        <div class="d-flex gap-2">

            <a
                href="/stock-movements"
                class="btn btn-dark"
            >
                <i class="bi bi-clock-history"></i>

                Stock History
            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="/stocks"
            >

                <div class="row align-items-end">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Search

                        </label>

                        <input
                            type="text"
                            id="stocks-live-search"
                            name="search"
                            class="form-control"
                            placeholder="Search product / store..."
                            value="{{ $search }}"
                        >

                    </div>

                    <div class="col-md-3">

                        <button
                            class="btn btn-primary w-100"
                        >
                            Search
                        </button>

                    </div>

                    <div class="col-md-3">

                        <a
                            href="/stocks"
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

                <table
                    class="table table-hover align-middle"
                >

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Product
                            </th>

                            <th>
                                Store
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="150">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody id="stocks-table-body">

                        @forelse($stocks as $s)

                            <tr>

                                <td>

                                    {{ $s->product_name }}

                                </td>

                                <td>

                                    {{ $s->store_name }}

                                </td>

                                <td>

                                    <span class="fw-bold">

                                        {{ $s->quantity }}

                                    </span>

                                </td>

                                <td>

                                    @if(
                                        $s->quantity
                                        <=
                                        setting(
                                            'low_stock_threshold',
                                            10
                                        )
                                    )

                                        <span class="badge bg-danger">

                                            Low Stock

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            Available

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <a
                                            href="/stocks/{{ $s->store_id }}/{{ $s->product_id }}/edit"
                                            class="btn btn-warning btn-sm"
                                        >

                                            Update

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="
                                        text-center
                                        text-muted
                                    "
                                >

                                    No stock data found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if(
                method_exists(
                    $stocks,
                    'links'
                )
            )

                <div class="d-flex justify-content-center">

                    {{ $stocks->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

<script>

document
.getElementById(
    'stocks-live-search'
)

.addEventListener(
    'keyup',
    function(){

        let search=this.value;

        fetch(
            `/stocks-live-search?search=${
                encodeURIComponent(
                    search
                )
            }`
        )

        .then(
            response=>
            response.json()
        )

        .then(
            data=>{

                let html='';

                if(
                    data.length===0
                ){

                    html=`

                    <tr>

                        <td
                            colspan="5"
                            class="
                            text-center
                            text-muted
                            "
                        >

                            No stock data found

                        </td>

                    </tr>

                    `;

                }

                else{

                    data.forEach(
                        stock=>{

                            let threshold=
                            {{ setting(
                                'low_stock_threshold',
                                10
                            ) }};

                            let status=
                            stock.quantity
                            <=
                            threshold

                            ?

                            `<span class="badge bg-danger">
                                Low Stock
                            </span>`

                            :

                            `<span class="badge bg-success">
                                Available
                            </span>`;

                            html+=`

                            <tr>

                                <td>

                                    ${stock.product_name}

                                </td>

                                <td>

                                    ${stock.store_name}

                                </td>

                                <td>

                                    <span class="fw-bold">

                                        ${stock.quantity}

                                    </span>

                                </td>

                                <td>

                                    ${status}

                                </td>

                                <td>

                                    <a
                                        href="/stocks/${stock.store_id}/${stock.product_id}/edit"
                                        class="
                                            btn
                                            btn-warning
                                            btn-sm
                                        "
                                    >

                                        Update

                                    </a>

                                </td>

                            </tr>

                            `;

                        }
                    );

                }

                document
                .getElementById(
                    'stocks-table-body'
                )
                .innerHTML=html;

            }

        );

    }

);

</script>

@endsection