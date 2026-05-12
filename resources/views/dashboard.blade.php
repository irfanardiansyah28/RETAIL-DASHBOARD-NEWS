@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">

        Dashboard

    </h1>

    <!-- KPI CARDS -->

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm border-0 text-center p-3">

                <h6>

                    Total Products

                </h6>

                <h2>

                    {{ $totalProducts }}

                </h2>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0 text-center p-3">

                <h6>

                    Total Orders

                </h6>

                <h2>

                    {{ $totalOrders }}

                </h2>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0 text-center p-3">

                <h6>

                    Total Customers

                </h6>

                <h2>

                    {{ $totalCustomers }}

                </h2>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow-sm border-0 text-center p-3">

                <h6>

                    Total Revenue

                </h6>

                <h2>

                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}

                </h2>

            </div>

        </div>

    </div>

    <!-- CHARTS -->

    <div class="row">

        <!-- MONTHLY ORDERS -->

        <div class="col-md-6">

            <div class="card shadow-sm border-0 p-3 mb-4">

                <h5 class="mb-3">

                    Monthly Orders Analytics

                </h5>

                <canvas
                    id="monthlyOrdersChart"
                    height="520"
                ></canvas>

            </div>

        </div>

        <!-- TOP PRODUCTS -->

        <div class="col-md-6">

            <div class="card shadow-sm border-0 p-3 mb-4">

                <h5 class="mb-3">

                    Top Selling Products

                </h5>

                <canvas
                    id="topProductsChart"
                    height="50"
                ></canvas>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    // MONTHLY ORDERS

    const monthlyLabels = @json(
        $monthlyOrders->pluck('month')
    );

    const monthlyData = @json(
        $monthlyOrders->pluck('total')
    );

    new Chart(

        document.getElementById(
            'monthlyOrdersChart'
        ),

        {

            type: 'bar',

            data: {

                labels: monthlyLabels,

                datasets: [{

                    label: 'Orders',

                    data: monthlyData

                }]

            }

        }

    );

    // TOP PRODUCTS

    const productLabels = @json(
        $topProducts->pluck('product_name')
    );

    const productData = @json(
        $topProducts->pluck('total_sold')
    );

    new Chart(

        document.getElementById(
            'topProductsChart'
        ),

        {

            type: 'pie',

            data: {

                labels: productLabels,

                datasets: [{

                    data: productData

                }]

            }

        }

    );

</script>

@endsection