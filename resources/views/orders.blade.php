@extends('layouts.app')

@section('content')

<h1>Orders</h1>

<table class="table table-bordered">

<tr>

    <th>Order ID</th>
    <th>Date</th>
    <th>Customer</th>
    <th>Total</th>

</tr>

@foreach($orders as $o)

<tr>

    <td>{{ $o->order_id }}</td>

    <td>{{ $o->order_date }}</td>

    <td>{{ $o->customer_name }}</td>

    <td>${{ number_format($o->total_amount,2) }}</td>

</tr>

@endforeach

</table>

@endsection