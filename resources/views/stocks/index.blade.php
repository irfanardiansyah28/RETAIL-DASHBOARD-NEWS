@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Stock Management

    </h1>

    <!-- SEARCH -->

    <form method="GET" class="mb-3">

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search product..."
            value="{{ $search }}"
        >

    </form>

    <table class="table table-bordered">

        <tr>

            <th>Product</th>
            <th>Store</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>

        </tr>

        @foreach($stocks as $s)

        <tr>

            <td>

                {{ $s->product_name }}

            </td>

            <td>

                {{ $s->store_name }}

            </td>

            <td>

                {{ $s->quantity }}

            </td>

            <td>

                @if($s->quantity < 10)

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

                <a
                    href="/stocks/{{ $s->store_id }}/{{ $s->product_id }}/edit"
                    class="btn btn-warning btn-sm"
                >

                    Update

                </a>

            </td>

        </tr>

        @endforeach

    </table>

    <div class="d-flex justify-content-center">

        {{ $stocks->links() }}

    </div>

</div>

@endsection