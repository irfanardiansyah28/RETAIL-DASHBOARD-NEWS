<!DOCTYPE html>
<html>

<head>

    <title>

        Invoice

    </title>

    <style>

        body {

            font-family: Arial, sans-serif;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;

        }

        table, th, td {

            border: 1px solid #ccc;

        }

        th, td {

            padding: 10px;

            text-align: left;

        }

        .header {

            margin-bottom: 30px;

        }

    </style>

</head>

<body>

    <div class="header">

        <h1>

            RetailOps Invoice

        </h1>

        <p>

            Invoice #{{ $order->order_id }}

        </p>

        <p>

            Date:
            {{ $order->order_date }}

        </p>

        <hr>

        <h3>

            Customer

        </h3>

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

    <table>

        <thead>

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

    <h2 style="margin-top:30px;">

        Grand Total:
        Rp {{ number_format($grandTotal,0,',','.') }}

    </h2>

</body>
</html>