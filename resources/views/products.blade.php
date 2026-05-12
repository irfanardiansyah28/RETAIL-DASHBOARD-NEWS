@extends('layouts.app')

@section('content')

<h1>Products</h1>

<table class="table table-bordered">

<tr>

    <th>Product</th>
    <th>Brand</th>
    <th>Category</th>
    <th>Year</th>
    <th>Price</th>

</tr>

@foreach($products as $p)

<tr>

    <td>{{ $p->product_name }}</td>

    <td>{{ $p->brand_name }}</td>

    <td>{{ $p->category_name }}</td>

    <td>{{ $p->model_year }}</td>

    <td>${{ $p->list_price }}</td>

</tr>

@endforeach

</table>

@endsection