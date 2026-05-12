@extends('layouts.app')

@section('content')

<h1>Product Detail</h1>

<div class="card p-4">

    <h3>{{ $product->product_name }}</h3>

    <hr>

    <p>
        <strong>Brand:</strong>
        {{ $product->brand_name }}
    </p>

    <p>
        <strong>Category:</strong>
        {{ $product->category_name }}
    </p>

    <p>
        <strong>Supplier:</strong>
        
    </p>

    <p>
        <strong>Model Year:</strong>
        {{ $product->model_year }}
    </p>

    <p>
        <strong>Price:</strong>
        ${{ $product->list_price }}
    </p>

</div>

@endsection