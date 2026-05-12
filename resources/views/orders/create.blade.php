@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Create Order

    </h1>

    <form method="POST" action="/orders">

        @csrf

        <!-- EXISTING CUSTOMER -->

        <div class="mb-3">

            <label class="form-label">

                Select Existing Customer

            </label>

            <select
                name="customer_id"
                class="form-control"
            >

                <option value="">

                    -- Select Customer --

                </option>

                @foreach($customers as $customer)

                    <option
                        value="{{ $customer->customer_id }}"
                    >

                        {{ $customer->first_name }}
                        {{ $customer->last_name }}
                        -
                        {{ $customer->email }}

                    </option>

                @endforeach

            </select>

        </div>

        <hr>

        <!-- NEW CUSTOMER -->

        <h5 class="mb-3">

            Or Create New Customer

        </h5>

        <div class="mb-3">

            <label class="form-label">

                Customer Name

            </label>

            <input
                type="text"
                name="new_customer_name"
                class="form-control"
            >

        </div>

        <div class="mb-3">

            <label class="form-label">

                Email

            </label>

            <input
                type="email"
                name="new_customer_email"
                class="form-control"
            >

        </div>

        <div class="mb-3">

            <label class="form-label">

                Phone

            </label>

            <input
                type="text"
                name="new_customer_phone"
                class="form-control"
            >

        </div>

        <div class="mb-3">

            <label class="form-label">

                City

            </label>

            <input
                type="text"
                name="new_customer_city"
                class="form-control"
            >

        </div>

        <hr>

        <!-- PRODUCT -->

        <div class="mb-3">

            <label class="form-label">

                Product

            </label>

            <select
                name="product_id"
                class="form-control"
                required
            >

                @foreach($products as $product)

                    <option
                        value="{{ $product->product_id }}"
                    >

                        {{ $product->product_name }}

                        -

                        Rp {{ number_format($product->list_price,0,',','.') }}

                    </option>

                @endforeach

            </select>

        </div>

        <!-- QUANTITY -->

        <div class="mb-3">

            <label class="form-label">

                Quantity

            </label>

            <input
                type="number"
                name="quantity"
                class="form-control"
                required
                min="1"
            >

        </div>

        <button class="btn btn-primary">

            Create Order

        </button>

    </form>

</div>

@endsection