@extends('layouts.app')

@section('content')

<h1>Edit Product</h1>

<form
    action="/products/{{ $product->product_id }}"
    method="POST"
>

@csrf
@method('PUT')

<div class="mb-3">

    <label>Product Name</label>

    <input
        type="text"
        name="product_name"
        class="form-control"
        value="{{ $product->product_name }}"
    >

</div>

<div class="mb-3">

    <label>Model Year</label>

    <input
        type="number"
        name="model_year"
        class="form-control"
        value="{{ $product->model_year }}"
    >

</div>

<div class="mb-3">

    <label>Price</label>

    <input
        type="number"
        name="list_price"
        class="form-control"
        value="{{ $product->list_price }}"
    >

</div>

<div class="mb-3">

    <label>Brand</label>

    <select
        name="brand_id"
        class="form-control"
    >

        @foreach($brands as $b)

        <option
            value="{{ $b->brand_id }}"

            @if(
                $product->brand_id
                ==
                $b->brand_id
            )

                selected

            @endif
        >

            {{ $b->brand_name }}

        </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label>Category</label>

    <select
        name="category_id"
        class="form-control"
    >

        @foreach($categories as $c)

        <option
            value="{{ $c->category_id }}"

            @if(
                $product->category_id
                ==
                $c->category_id
            )

                selected

            @endif
        >

            {{ $c->category_name }}

        </option>

        @endforeach

    </select>

</div>

<button class="btn btn-primary">

    Update

</button>

</form>

@endsection