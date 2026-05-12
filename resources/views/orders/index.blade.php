@extends('layouts.app')

@section('content')

<h1>Orders</h1>

<form method="GET" class="mb-3">

    <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search Order ID..."
        value="{{ $search }}"
    >

</form>

<table class="table table-bordered">

<tr>

    <th>Order ID</th>
    <th>Date</th>
    <th>Customer</th>
    <th>Total</th>
    <th>Action</th>
    <th>Status</th>

</tr>

@foreach($orders as $o)

<tr>

    <td>{{ $o->order_id }}</td>

    <td>{{ $o->order_date }}</td>

    <td>{{ $o->customer_name }}</td>

    <td>
        ${{ number_format($o->total_amount,2) }}
    </td>

    <td>

        <a
            href="/orders/{{ $o->order_id }}"
            class="btn btn-info btn-sm"
        >

            Detail

        </a>

    </td>

    <td>

    @if($o->status == 'Pending')

        <span class="badge bg-warning">

            Pending

        </span>

    @elseif($o->status == 'Paid')

        <span class="badge bg-success">

            Paid

        </span>

    @elseif($o->status == 'Cancelled')

        <span class="badge bg-danger">

            Cancelled

        </span>

    @endif

</td>

</tr>

@endforeach

</table>

@endsection