@extends('layouts.app')

@section('content')

<h1>

    Products

</h1>

<a href="/products/create"
   class="btn btn-primary mb-3">

   Add Product

</a>

<form
    method="GET"
    action="/products"
>

    <div class="row mb-3">

        <div class="col-md-4">

            <input
                type="text"
                id="live-search"
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

<table class="table table-bordered table-hover bg-white">
<tbody id="products-table-body">

    <tr>

        <th>Name</th>
        <th>Brand</th>
        <th>Category</th>
        <th>Price</th>
        <th width="250">Action</th>

    </tr>

    @foreach($products as $p)

    <tr>

        <td>

            {{ $p->product_name }}

        </td>

        <td>

            {{ $p->brand_name }}

        </td>

        <td>

            {{ $p->category_name }}

        </td>

        <td>

            ${{ number_format($p->list_price,2) }}

        </td>

        <td>

            <!-- DETAIL -->

            <a
                href="/products/{{ $p->product_id }}"
                class="btn btn-info btn-sm"
            >

                Detail

            </a>

            <!-- EDIT -->

            <a
                href="/products/{{ $p->product_id }}/edit"
                class="btn btn-warning btn-sm"
            >

                Edit

            </a>

            <!-- DELETE -->

            <form
                action="/products/{{ $p->product_id }}"
                method="POST"
                style="display:inline;"
                class="delete-form"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger btn-sm"
                >

                    Delete

                </button>

            </form>

        </td>

    </tr>

    @endforeach
    </tbody>

</table>

<!-- PAGINATION -->

<div class="d-flex justify-content-center mt-4">

    {{ $products->links() }}

</div>

<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    // DELETE CONFIRMATION

    document.querySelectorAll('.delete-form')

    .forEach(form => {

        form.addEventListener('submit', function(e){

            e.preventDefault();

            Swal.fire({

                title: 'Are you sure?',

                text: 'Product will be deleted permanently',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#dc2626',

                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Yes Delete'

            }).then((result) => {

                if(result.isConfirmed){

                    form.submit();

                }

            });

        });

    });

</script>

<script>

document
.getElementById('live-search')

.addEventListener('keyup', function(){

    let search = this.value;

    fetch(
        `/products-live-search?search=${search}`
    )

    .then(response => response.json())

    .then(data => {

        let html = '';

        data.forEach(product => {

            html += `
            
            <tr>

                <td>${product.product_name}</td>

                <td>${product.brand_name}</td>

                <td>${product.category_name}</td>

                <td>$${Number(product.list_price).toLocaleString()}</td>

                <td>

                    <a
                        href="/products/${product.product_id}"
                        class="btn btn-info btn-sm"
                    >
                        Detail
                    </a>

                    <a
                        href="/products/${product.product_id}/edit"
                        class="btn btn-warning btn-sm"
                    >
                        Edit
                    </a>

                    <form
                        action="/products/${product.product_id}"
                        method="POST"
                        style="display:inline;"
                    >

                        <input
                            type="hidden"
                            name="_token"
                            value="{{ csrf_token() }}"
                        >

                        <input
                            type="hidden"
                            name="_method"
                            value="DELETE"
                        >

                        <button
                            class="btn btn-danger btn-sm"
                        >

                            Delete

                        </button>

                    </form>

                </td>

            </tr>
            
            `;

        });

        document
        .getElementById('products-table-body')
        .innerHTML = html;

    });

});

</script>
@endsection