<!DOCTYPE html>
<html>

<head>

    <title>Retail Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {

            margin: 0;

            background: #f4f6f9;

            font-family: Arial, sans-serif;

        }

        .wrapper {

            display: flex;

        }

        /* SIDEBAR */

        .sidebar {

            width: 240px;

            height: 100vh;

            background: linear-gradient(
                180deg,
                #111827,
                #1f2937
            );

            position: fixed;

            top: 0;
            left: 0;

            display: flex;

            flex-direction: column;

            justify-content: space-between;

            overflow-y: auto;

            box-shadow:
                2px 0 10px rgba(0,0,0,0.1);

        }

        .sidebar-top {

            padding-top: 20px;

        }

        /* LOGO */

        .logo {

            color: white;

            text-align: center;

            margin-bottom: 30px;

            font-weight: bold;

            font-size: 28px;

        }

        /* MENU */

        .sidebar a {

            display: block;

            color: #d1d5db;

            padding: 14px 24px;

            text-decoration: none;

            transition: 0.3s;

            font-size: 15px;

        }

        .sidebar a:hover {

            background: #2563eb;

            color: white;

            padding-left: 30px;

        }

        /* CONTENT */

        .main-content {

            margin-left: 240px;

            width: calc(100% - 240px);

            padding: 30px;

        }

        /* CARD */

        .card-custom {

            background: white;

            border-radius: 16px;

            padding: 20px;

            box-shadow:
                0 2px 10px rgba(0,0,0,0.05);

        }

        /* TABLE */

        table {

            background: white;

            border-radius: 12px;

            overflow: hidden;

        }

        /* BUTTON */

        .btn {

            border-radius: 10px;

        }

        /* SEARCH */

        input.form-control {

            border-radius: 10px;

        }

        /* PAGINATION */

        .pagination {

            margin-top: 20px;

        }

        .pagination .page-link {

            border-radius: 8px;

            margin: 0 3px;

        }

        /* SIDEBAR BOTTOM */

        .sidebar-bottom {

            padding: 20px;

            border-top:
                1px solid rgba(255,255,255,0.1);

        }

        /* LOGOUT */

        .logout-link {

            background: none;

            border: none;

            color: #9ca3af;

            width: 100%;

            text-align: left;

            padding: 12px 24px;

            font-size: 15px;

            cursor: pointer;

            transition: 0.3s;

        }

        .logout-link:hover {

            color: white;

            background: #dc2626;

            border-radius: 10px;

        }

        /* MOBILE */

        @media(max-width:768px){

            .sidebar {

                width: 200px;

            }

            .main-content {

                margin-left: 200px;

                width: calc(100% - 200px);

            }

        }

    </style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <!-- TOP -->

        <div class="sidebar-top">

            <h3 class="logo">

                RetailOps

            </h3>

            <a href="/">

    Dashboard

</a>

            <a href="/products">

                Products

            </a>

            <a href="/orders">

                Orders

            </a>

            <a href="/customers">

                Customers

            </a>

            <a href="/stocks">

                Stocks

            </a>

            <a href="/orders/create">

                Create Order

            </a>

        </div>

        <!-- BOTTOM -->

        <div class="sidebar-bottom">

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-link"
                >

                    Logout

                </button>

            </form>

        </div>

    </div>

    <!-- CONTENT -->

    <div class="main-content">

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>