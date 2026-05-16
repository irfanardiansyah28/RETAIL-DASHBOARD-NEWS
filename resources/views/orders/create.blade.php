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

        <!-- ORDER ITEMS -->

        <h5 class="mb-3">

            Order Items

        </h5>

        <div id="items-wrapper">

            <div class="row mb-3 item-row">

                <!-- PRODUCT -->

                <div class="col-md-6">

                    <label class="form-label">

                        Product

                    </label>

                    <select
                        name="products[]"
                        class="form-control product-select"
                        required
                    >

                        <option value="">

                            -- Select Product --

                        </option>

                        @foreach($products as $product)

                            <option
                                value="{{ $product->product_id }}"
                                data-price="{{ $product->list_price }}"
                            >

                                {{ $product->product_name }}

                                -

                                Rp {{ number_format($product->list_price,0,',','.') }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- QTY -->

                <div class="col-md-2">

                    <label class="form-label">

                        Qty

                    </label>

                    <input
                        type="number"
                        name="quantities[]"
                        class="form-control quantity-input"
                        value="1"
                        min="1"
                        required
                    >

                </div>

                <!-- PRICE -->

                <div class="col-md-2">

                    <label class="form-label">

                        Price

                    </label>

                    <input
                        type="text"
                        class="form-control price-display"
                        readonly
                    >

                </div>

                <!-- SUBTOTAL -->

                <div class="col-md-2">

                    <label class="form-label">

                        Subtotal

                    </label>

                    <input
                        type="text"
                        class="form-control subtotal-display"
                        readonly
                    >

                </div>

            </div>

        </div>

        <!-- ADD PRODUCT BUTTON -->

        <button
            type="button"
            class="btn btn-success mb-3"
            id="add-item"
        >

            + Add Product

        </button>

        <!-- GRAND TOTAL -->

        <div class="mb-4">

            <h4>

                Grand Total:
                Rp <span id="grand-total">0</span>

            </h4>

        </div>

        <!-- SUBMIT -->

        <button class="btn btn-primary">

            Create Order

        </button>

    </form>

</div>

<script>

    function calculateTotals() {

        let grandTotal = 0;

        document.querySelectorAll('.item-row').forEach(row => {

            const product =
                row.querySelector('.product-select');

            const qty =
                row.querySelector('.quantity-input');

            const priceDisplay =
                row.querySelector('.price-display');

            const subtotalDisplay =
                row.querySelector('.subtotal-display');

            const selectedOption =
                product.options[product.selectedIndex];

            const price =
                selectedOption.dataset.price || 0;

            const subtotal =
                price * qty.value;

            priceDisplay.value =
                Number(price).toLocaleString();

            subtotalDisplay.value =
                Number(subtotal).toLocaleString();

            grandTotal += subtotal;

        });

        document.getElementById(
            'grand-total'
        ).innerText =
            grandTotal.toLocaleString();
    }

    document.addEventListener('change', function(e){

        if (
            e.target.classList.contains('product-select') ||
            e.target.classList.contains('quantity-input')
        ) {

            calculateTotals();

        }

    });

    document.getElementById('add-item')

        .addEventListener('click', function(){

            const wrapper =
                document.getElementById('items-wrapper');

            const firstRow =
                document.querySelector('.item-row');

            const clone =
                firstRow.cloneNode(true);

            clone.querySelectorAll('input').forEach(input => {

                if (
                    input.classList.contains('quantity-input')
                ) {

                    input.value = 1;

                } else {

                    input.value = '';

                }

            });

            clone.querySelector('.product-select').selectedIndex = 0;

            wrapper.appendChild(clone);

        });

</script>

@endsection