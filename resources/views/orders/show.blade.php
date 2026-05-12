@extends('layouts.app')

@section('content')

<h1>Order Detail</h1>

<div class="card p-4 mb-4">

    <h4>Order Information</h4>

    <hr>

    <p>

        <strong>Order ID:</strong>

        {{ $order->order_id }}

    </p>

    <p>

        <strong>Order Date:</strong>

        {{ $order->order_date }}

    </p>

    <p>

        <strong>Customer:</strong>

        {{ $order->customer_name }}

    </p>

    <p>

        <strong>Email:</strong>

        {{ $order->email }}

    </p>

    <p>

        <strong>Phone:</strong>

        {{ $order->phone }}

    </p>

</div>

<div class="card p-4">

    <h4>Items</h4>

    <table class="table table-bordered">

        <tr>

            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Subtotal</th>

        </tr>

        @foreach($items as $i)

        <tr>

            <td>{{ $i->product_name }}</td>

            <td>{{ $i->quantity }}</td>

            <td>
                ${{ number_format($i->list_price,2) }}
            </td>

            <td>

                {{ $i->discount * 100 }}%

            </td>

            <td>

                ${{ number_format($i->subtotal,2) }}

            </td>

        </tr>

        @endforeach

    </table>

    <div class="text-end">

        <h3>

            Grand Total:
            ${{ number_format($grandTotal,2) }}

        </h3>

    </div>

</div>

<form
    action="/orders/{{ $order->order_id }}/pay"
    method="POST"
>

    @csrf

    <button class="btn btn-success">

        Mark as Paid

    </button>

</form>

@endsection