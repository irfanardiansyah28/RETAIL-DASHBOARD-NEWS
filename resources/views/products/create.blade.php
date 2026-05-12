@extends('layouts.app')

@section('content')

<h1>Add Product</h1>

<form action="/products" method="POST">

@csrf

<div class="mb-3">

    <label>Product Name</label>

    <input
        type="text"
        name="product_name"
        class="form-control"
    >

</div>

<div class="mb-3">

    <label>Brand</label>

    <select
        name="brand_id"
        class="form-control"
    >

        @foreach($brands as $b)

        <option value="{{ $b->brand_id }}">

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

        <option value="{{ $c->category_id }}">

            {{ $c->category_name }}

        </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label>Model Year</label>

    <input
        type="number"
        name="model_year"
        class="form-control"
    >

</div>

<div class="mb-3">

    <label>Price</label>

    <input
        type="number"
        name="list_price"
        class="form-control"
    >

</div>

<button class="btn btn-success">

    Save

</button>

</form>

@endsection