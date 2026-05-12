@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Update Stock

    </h1>

    <div class="card p-4">

        <form method="POST" action="/stocks/update">

            @csrf

            <input
                type="hidden"
                name="store_id"
                value="{{ $stock->store_id }}"
            >

            <input
                type="hidden"
                name="product_id"
                value="{{ $stock->product_id }}"
            >

            <div class="mb-3">

                <label>Product</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $stock->product_name }}"
                    disabled
                >

            </div>

            <div class="mb-3">

                <label>Store</label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $stock->store_name }}"
                    disabled
                >

            </div>

            <div class="mb-3">

                <label>Quantity</label>

                <input
                    type="number"
                    name="quantity"
                    class="form-control"
                    value="{{ $stock->quantity }}"
                >

            </div>

            <button class="btn btn-primary">

                Update Stock

            </button>

        </form>

    </div>

</div>

@endsection