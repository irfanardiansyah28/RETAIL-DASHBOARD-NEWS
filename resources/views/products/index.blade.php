@extends('layouts.app')

@section('content')

<h1>Products</h1>

<a href="/products/create"
   class="btn btn-primary mb-3">

   Add Product

</a>

<form method="GET" action="/products">

    <div class="row mb-3">

        <div class="col-md-4">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search product..."
                value="{{ request('search') }}"
            >

        </div>

        <div class="col-md-2">

            <button class="btn btn-primary">

                Search

            </button>

        </div>

    </div>

</form>

<table class="table table-bordered">

<tr>

    <th>Name</th>
    <th>Brand</th>
    <th>Category</th>
    <th>Price</th>
    <th>Action</th>

</tr>

@foreach($products as $p)

<tr>

    <td>{{ $p->product_name }}</td>

    <td>{{ $p->brand_name }}</td>

    <td>{{ $p->category_name }}</td>

    <td>${{ $p->list_price }}</td>

    <td>

    <a href="/products/{{ $p->product_id }}"
   class="btn btn-info btn-sm">

   Detail

</a>

        <a href="/products/{{ $p->product_id }}/edit"
           class="btn btn-warning btn-sm">

           Edit

        </a>

        <form
            action="/products/{{ $p->product_id }}"
            method="POST"
            style="display:inline;"
        >

            @csrf
            @method('DELETE')

            <button class="btn btn-danger btn-sm">

                Delete

            </button>

        </form>

    </td>

</tr>

@endforeach

</table>
<div class="d-flex justify-content-center mt-3">
{{ $products->links() }}
@endsection