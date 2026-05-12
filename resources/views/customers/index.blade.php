@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-3">

        <h1>

            Customers

        </h1>

        <a
            href="/customers/create"
            class="btn btn-primary"
        >

            Add Customer

        </a>

    </div>

    <!-- SEARCH -->

    <form method="GET" class="mb-3">

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search customer..."
            value="{{ $search }}"
        >

    </form>

    <table class="table table-bordered">

        <tr>

            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Action</th>

        </tr>

        @foreach($customers as $c)

        <tr>

            <td>

                {{ $c->first_name }}
                {{ $c->last_name }}

            </td>

            <td>

                {{ $c->email }}

            </td>

            <td>

                {{ $c->phone }}

            </td>

            <td>

                {{ $c->city }}

            </td>

            <td>

                <a
                    href="/customers/{{ $c->customer_id }}"
                    class="btn btn-info btn-sm"
                >

                    Detail

                </a>

                <a
                    href="/customers/{{ $c->customer_id }}/edit"
                    class="btn btn-warning btn-sm"
                >

                    Edit

                </a>

                <form
                    action="/customers/{{ $c->customer_id }}"
                    method="POST"
                    style="display:inline;"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger btn-sm"
                    >

                        Delete

                    </button>

                </form>

            </td>

        </tr>

        @endforeach

    </table>

    <div class="d-flex justify-content-center">

        {{ $customers->links() }}

    </div>

</div>

@endsection