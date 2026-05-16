@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-4">

        <h1>

            Invoice Preview

        </h1>

        <a
            href="/orders/{{ $order->order_id }}/invoice"
            class="btn btn-dark"
        >

            Download PDF

        </a>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3>

                RetailOps Invoice

            </h3>

            <hr>

            <div class="row mb-4">

                <div class="col-md-6">

                    <h5>

                        Customer

                    </h5>

                    <p>

                        {{ $order->customer_name }}

                    </p>

                    <p>

                        {{ $order->email }}

                    </p>

                    <p>

                        {{ $order->phone }}

                    </p>

                </div>

                <div class="col-md-6 text-end">

                    <h5>

                        Invoice #

                        {{ $order->order_id }}

                    </h5>

                    <p>

                        Date:
                        {{ $order->order_date }}

                    </p>

                </div>

            </div>

            <table class="table table-bordered">

                <thead class="table-dark">

                    <tr>

                        <th>

                            Product

                        </th>

                        <th>

                            Qty

                        </th>

                        <th>

                            Price

                        </th>

                        <th>

                            Subtotal

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($items as $item)

                    <tr>

                        <td>

                            {{ $item->product_name }}

                        </td>

                        <td>

                            {{ $item->quantity }}

                        </td>

                        <td>

                            Rp {{ number_format($item->list_price,0,',','.') }}

                        </td>

                        <td>

                            Rp {{ number_format($item->subtotal,0,',','.') }}

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="text-end mt-4">

                <h3>

                    Grand Total:

                    Rp {{ number_format($grandTotal,0,',','.') }}

                </h3>

            </div>

        </div>

    </div>

</div>

@endsection