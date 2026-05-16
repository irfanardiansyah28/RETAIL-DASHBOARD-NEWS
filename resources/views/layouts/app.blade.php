<!DOCTYPE html>
<html>

<head>

    <title>Retail Dashboard</title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
            overflow-x: hidden;
            scroll-behavior: auto;
        }

        body {
            background: #f3f4f6;
            font-family: Inter, Arial, sans-serif;
            transition: 0.3s;
        }

        .wrapper {
            display: flex;
            align-items: flex-start;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            color: white;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35);
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 18px 24px 10px;
            flex-shrink: 0;
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 10px 14px 16px;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 999px;
        }

        .sidebar-menu a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 16px;
            transition: 0.25s ease;
            display: flex;
            align-items: center;
            gap: 13px;
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .sidebar-menu a i {
            font-size: 17px;
            opacity: 0.9;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 10px 24px rgba(37,99,235,0.35);
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 14px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(15, 23, 42, 0.98);
        }

        .user-card {
            background: rgba(255,255,255,0.07);
            padding: 15px;
            border-radius: 18px;
            margin-bottom: 12px;
        }

        .user-name {
            color: white;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .user-role {
            color: #cbd5e1;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .logout-link {
            width: 100%;
            border: none;
            background: transparent;
            color: #cbd5e1;
            padding: 13px 14px;
            border-radius: 14px;
            text-align: left;
            transition: 0.25s ease;
            font-size: 15px;
            font-weight: 500;
        }

        .logout-link:hover {
            background: rgba(239, 68, 68, 0.18);
            color: #fecaca;
        }

        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            min-height: 100vh;
            padding: 20px 28px 28px;
            background: #f3f4f6;
            transition: 0.3s;
            position: relative;
            top: 0;
        }

        .topbar {
            background: rgba(255,255,255,0.95);
            border-radius: 22px;
            padding: 18px 24px;
            margin-top: 0;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(15,23,42,0.08);
            transition: 0.3s;
        }

        .topbar-title h4 {
            margin: 0;
            font-weight: 800;
        }

        .topbar-title p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .container,
        .container-fluid {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        table {
            border-radius: 16px;
            overflow: hidden;
        }

        .btn {
            border-radius: 12px;
            font-weight: 500;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 14px;
        }

        .pagination .page-link {
            border-radius: 10px;
            margin: 0 3px;
        }

        #darkModeToggle {
            border-radius: 14px;
            width: 45px;
            height: 45px;
        }

        .role-badge {
            background: #111827;
            color: white;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .global-search-wrapper {
            position: relative;
            width: 420px;
        }

        .global-search-input {
            height: 48px !important;
            border-radius: 18px !important;
            padding-left: 60px !important;
            padding-right: 16px !important;
            font-size: 15px !important;
        }

        .global-search-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 20px;
            z-index: 5;
            pointer-events: none;
        }

        .global-search-dropdown {
            position: absolute;
            top: 52px;
            left: 0;
            width: 460px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.18);
            z-index: 3000;
            display: none;
            overflow: hidden;
        }

        .global-search-dropdown.show {
            display: block;
        }

        .global-search-section-title {
            padding: 12px 16px;
            font-weight: 800;
            background: #f3f4f6;
            font-size: 13px;
        }

        .global-search-item {
            display: block;
            padding: 13px 16px;
            text-decoration: none;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
        }

        .global-search-item:hover {
            background: #f9fafb;
        }

        .global-search-title {
            font-weight: 700;
            font-size: 14px;
        }

        .global-search-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }

        .global-search-empty {
            padding: 22px;
            text-align: center;
            color: #6b7280;
        }

        .notification-wrapper {
            position: relative;
        }

        .notification-button {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            background: #dc2626;
            color: white;
            font-size: 11px;
            font-weight: 800;
            min-width: 21px;
            height: 21px;
            border-radius: 999px;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        .notification-dropdown {
            position: absolute;
            right: 0;
            top: 55px;
            width: 390px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.18);
            z-index: 2000;
            display: none;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-header {
            padding: 16px 18px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 800;
        }

        .notification-body {
            max-height: 420px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 14px 18px;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            display: block;
            color: #111827;
        }

        .notification-item:hover {
            background: #f9fafb;
        }

        .notification-title {
            font-weight: 800;
            font-size: 14px;
        }

        .notification-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 3px;
        }

        .notification-empty {
            padding: 24px;
            text-align: center;
            color: #6b7280;
        }

        body.dark-mode {
            background: #111827;
            color: #f9fafb;
        }

        body.dark-mode .main-content {
            background: #111827;
        }

        body.dark-mode .topbar,
        body.dark-mode .card {
            background: #1f2937;
            color: white;
        }

        body.dark-mode .topbar-title p,
        body.dark-mode .notification-text,
        body.dark-mode .global-search-subtitle {
            color: #d1d5db;
        }

        body.dark-mode table,
        body.dark-mode .table,
        body.dark-mode .card-body {
            color: white;
        }

        body.dark-mode .table-dark {
            background: #374151;
        }

        body.dark-mode .table-hover tbody tr:hover {
            background: rgba(255,255,255,0.05);
        }

        body.dark-mode .form-control {
            background: #374151;
            border: 1px solid #4b5563;
            color: white;
        }

        body.dark-mode .form-control::placeholder {
            color: #d1d5db;
        }

        body.dark-mode .pagination .page-link,
        body.dark-mode .page-link {
            background: #1f2937;
            border-color: #374151;
            color: white;
        }

        body.dark-mode .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
        }

        body.dark-mode .swal2-popup {
            background: #1f2937 !important;
            color: white !important;
        }

        body.dark-mode .notification-dropdown,
        body.dark-mode .global-search-dropdown {
            background: #1f2937;
            color: white;
        }

        body.dark-mode .notification-header {
            border-bottom: 1px solid #374151;
        }

        body.dark-mode .notification-item,
        body.dark-mode .global-search-item {
            color: white;
            border-bottom: 1px solid #374151;
        }

        body.dark-mode .notification-item:hover,
        body.dark-mode .global-search-item:hover {
            background: #374151;
        }

        body.dark-mode .global-search-section-title {
            background: #374151;
        }

        @media(max-width: 992px) {

            .sidebar {
                width: 240px;
            }

            .main-content {
                margin-left: 240px;
                width: calc(100% - 240px);
                padding: 20px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .topbar-right {
                width: 100%;
                justify-content: flex-end;
                flex-wrap: wrap;
            }

            .global-search-wrapper {
                width: 100%;
            }

            .global-search-dropdown {
                width: 100%;
            }

        }

        @media(max-width: 768px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                min-height: auto;
            }

            .wrapper {
                flex-direction: column;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 16px;
            }

            .notification-dropdown {
                right: -80px;
                width: 320px;
            }

        }

    </style>

</head>

<body>

<div class="wrapper">

    <div class="sidebar">

        <div class="sidebar-brand">
            <div class="logo">
                RetailOps
            </div>
        </div>

        <div class="sidebar-menu">

            @if(auth()->user()->role == 'admin')

                <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>

                <a href="/analytics/order-heatmap" class="{{ request()->is('analytics/order-heatmap*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i>
                    Analytics
                </a>


                <a
                    href="/analytics/rule-performance"
                    class="{{ request()->is('analytics/rule-performance*') ? 'active':'' }}"
                    >

                    <i class="bi bi-graph-up-arrow"></i>

                    Rule Analytics

                    </a>
                <a href="/analytics/inventory-forecast" class="{{ request()->is('analytics/inventory-forecast*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i>
                    Inventory Forecast
                </a>

                <a href="/analytics/store-performance" class="{{ request()->is('analytics/store-performance*') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i>
                        Store Ranking
                    </a>

                <a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    Products
                </a>

                <a href="/orders" class="{{ request()->is('orders') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i>
                    Orders
                </a>

                <a href="/orders/create" class="{{ request()->is('orders/create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    Create Order
                </a>

                <a href="/customers" class="{{ request()->is('customers*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Customers
                </a>

                <a href="/stocks" class="{{ request()->is('stocks*') ? 'active' : '' }}">
                    <i class="bi bi-archive"></i>
                    Stocks
                </a>

                <a href="/stock-movements" class="{{ request()->is('stock-movements*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    Stock Movements
                </a>

                <a href="/audit-dashboard" class="{{ request()->is('audit-dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i>
                    Audit Dashboard
                </a>

                <a href="/risk-flags" class="{{ request()->is('risk-flags*') ? 'active' : '' }}">
                    <i class="bi bi-flag-fill"></i>
                    Risk Flags
                </a>

                <a
                    href="/investigation"
                    class="{{ request()->is('investigation*') ? 'active':'' }}"
                    >

                    <i class="bi bi-search"></i>

                    Investigation Center

                    </a>

                <a href="/scenario-rules" class="{{ request()->is('scenario-rules*') ? 'active' : '' }}">
                    <i class="bi bi-sliders"></i>
                    Scenario Builder
                </a>
                <a
                href="/entity-links"
                class="{{ request()->is('entity-links*') ? 'active' : '' }}"
                >
                <i class="bi bi-diagram-3"></i>

                Entity Links
                </a>

                <a href="/approvals" class="{{ request()->is('approvals*') ? 'active' : '' }}">
                    <i class="bi bi-check2-square"></i>
                    Approvals
                </a>

                <a href="/users" class="{{ request()->is('users*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    Users
                </a>

                <a href="/settings" class="{{ request()->is('settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    Settings
                </a>

                <a href="/activity-logs" class="{{ request()->is('activity-logs*') ? 'active' : '' }}">
                    <i class="bi bi-clock"></i>
                    Activity Logs
                </a>

            @else

                <a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    Products
                </a>

                <a href="/orders" class="{{ request()->is('orders') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i>
                    Orders
                </a>

                <a href="/orders/create" class="{{ request()->is('orders/create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    Create Order
                </a>

                <a href="/customers" class="{{ request()->is('customers*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Customers
                </a>

                <a href="/stocks" class="{{ request()->is('stocks*') ? 'active' : '' }}">
                    <i class="bi bi-archive"></i>
                    Stocks
                </a>

            @endif

        </div>

        <div class="sidebar-footer">

            <div class="user-card">

                <div class="user-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="user-role">
                    {{ auth()->user()->role }}
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-link">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </button>

            </form>

        </div>

    </div>

    <div class="main-content">

        <div class="topbar">

            <div class="topbar-title">

                <h4>
                    Welcome, {{ auth()->user()->name }}
                </h4>

                <p>
                    Retail Management System Dashboard
                </p>

            </div>

            <div class="topbar-right">

                <div class="global-search-wrapper">

                    <i class="bi bi-search global-search-icon"></i>

                    <input
                        type="text"
                        id="globalSearchInput"
                        class="form-control global-search-input"
                        placeholder="Search anything..."
                        autocomplete="off"
                    >

                    <div
                        id="globalSearchDropdown"
                        class="global-search-dropdown"
                    >
                        <div class="global-search-empty">
                            Type at least 2 characters
                        </div>
                    </div>

                </div>

                <div class="notification-wrapper">

                    <button
                        type="button"
                        id="notificationButton"
                        class="btn btn-light notification-button"
                    >
                        <i class="bi bi-bell-fill"></i>

                        <span
                            id="notificationBadge"
                            class="notification-badge"
                        >
                            0
                        </span>
                    </button>

                    <div
                        id="notificationDropdown"
                        class="notification-dropdown"
                    >

                        <div class="notification-header">
                            Notifications
                        </div>

                        <div
                            id="notificationBody"
                            class="notification-body"
                        >
                            <div class="notification-empty">
                                Loading notifications...
                            </div>
                        </div>

                    </div>

                </div>

                <button id="darkModeToggle" class="btn btn-dark">
                    <i class="bi bi-moon-stars-fill"></i>
                </button>

                <div class="role-badge">
                    {{ strtoupper(auth()->user()->role) }}
                </div>

            </div>

        </div>

        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))

<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>

@endif

@if(session('error'))

<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>

@endif

<script>

    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationBody = document.getElementById('notificationBody');

    if (
        notificationButton
        &&
        notificationDropdown
        &&
        notificationBadge
        &&
        notificationBody
    ) {

        notificationButton.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function() {
            notificationDropdown.classList.remove('show');
        });

        notificationDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        function loadNotifications() {

            fetch('/notifications')

                .then(response => response.json())

                .then(data => {

                    if (data.total > 0) {
                        notificationBadge.style.display = 'flex';
                        notificationBadge.innerText = data.total;
                    } else {
                        notificationBadge.style.display = 'none';
                        notificationBadge.innerText = 0;
                    }

                    let html = '';

                    if (data.low_stock_count > 0) {

                        html += `
                            <div class="notification-header">
                                Low Stock (${data.low_stock_count})
                            </div>
                        `;

                        data.low_stocks.forEach(item => {

                            html += `
                                <a
                                    href="/stocks/${item.store_id}/${item.product_id}/edit"
                                    class="notification-item"
                                >
                                    <div class="notification-title">
                                        Low Stock Alert
                                    </div>

                                    <div class="notification-text">
                                        ${item.product_name}
                                        at
                                        ${item.store_name}
                                        only has
                                        <strong>${item.quantity}</strong>
                                        stock left.
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if (data.pending_order_count > 0) {

                        html += `
                            <div class="notification-header">
                                Pending Orders (${data.pending_order_count})
                            </div>
                        `;

                        data.pending_orders.forEach(order => {

                            html += `
                                <a
                                    href="/orders/${order.order_id}"
                                    class="notification-item"
                                >
                                    <div class="notification-title">
                                        Pending Order #${order.order_id}
                                    </div>

                                    <div class="notification-text">
                                        Customer: ${order.customer_name}
                                        <br>
                                        Date: ${order.order_date}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if (data.risk_flag_count > 0) {

                        html += `
                            <div class="notification-header">
                                Risk Flags (${data.risk_flag_count})
                            </div>
                        `;

                        data.risk_flags.forEach(flag => {

                            let severityClass = 'text-primary';

                            if(flag.severity === 'High'){
                                severityClass = 'text-danger';
                            }

                            if(flag.severity === 'Medium'){
                                severityClass = 'text-warning';
                            }

                            html += `
                                <a
                                    href="/risk-flags"
                                    class="notification-item"
                                >
                                    <div class="notification-title ${severityClass}">
                                        🚩 ${flag.severity} Risk
                                    </div>

                                    <div class="notification-text">
                                        ${flag.title}
                                        <br>
                                        ${flag.module ?? '-'} • ${flag.risk_type}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if (data.total === 0) {

                        html = `
                            <div class="notification-empty">
                                No notifications
                            </div>
                        `;

                    }

                    notificationBody.innerHTML = html;

                })

                .catch(() => {

                    notificationBody.innerHTML = `
                        <div class="notification-empty">
                            Failed to load notifications
                        </div>
                    `;

                });

        }

        loadNotifications();

        setInterval(loadNotifications, 30000);

    }

</script>

<script>

    const globalSearchInput = document.getElementById('globalSearchInput');
    const globalSearchDropdown = document.getElementById('globalSearchDropdown');

    if (
        globalSearchInput
        &&
        globalSearchDropdown
    ) {

        globalSearchInput.addEventListener('keyup', function(){

            let keyword = this.value;

            if(keyword.length < 2){

                globalSearchDropdown.classList.remove('show');

                globalSearchDropdown.innerHTML = `
                    <div class="global-search-empty">
                        Type at least 2 characters
                    </div>
                `;

                return;

            }

            fetch(`/global-search?search=${encodeURIComponent(keyword)}`)

                .then(response => response.json())

                .then(data => {

                    let html = '';

                    if(data.products.length > 0){

                        html += `
                            <div class="global-search-section-title">
                                Products
                            </div>
                        `;

                        data.products.forEach(item => {

                            html += `
                                <a
                                    href="/products/${item.product_id}"
                                    class="global-search-item"
                                >
                                    <div class="global-search-title">
                                        ${item.product_name}
                                    </div>

                                    <div class="global-search-subtitle">
                                        ${item.brand_name} • ${item.category_name}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if(data.orders.length > 0){

                        html += `
                            <div class="global-search-section-title">
                                Orders
                            </div>
                        `;

                        data.orders.forEach(item => {

                            html += `
                                <a
                                    href="/orders/${item.order_id}"
                                    class="global-search-item"
                                >
                                    <div class="global-search-title">
                                        Order #${item.order_id}
                                    </div>

                                    <div class="global-search-subtitle">
                                        ${item.customer_name} • ${item.status}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if(data.customers.length > 0){

                        html += `
                            <div class="global-search-section-title">
                                Customers
                            </div>
                        `;

                        data.customers.forEach(item => {

                            html += `
                                <a
                                    href="/customers/${item.customer_id}"
                                    class="global-search-item"
                                >
                                    <div class="global-search-title">
                                        ${item.first_name} ${item.last_name}
                                    </div>

                                    <div class="global-search-subtitle">
                                        ${item.email ?? '-'} • ${item.phone ?? '-'}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if(data.stocks.length > 0){

                        html += `
                            <div class="global-search-section-title">
                                Stocks
                            </div>
                        `;

                        data.stocks.forEach(item => {

                            html += `
                                <a
                                    href="/stocks/${item.store_id}/${item.product_id}/edit"
                                    class="global-search-item"
                                >
                                    <div class="global-search-title">
                                        ${item.product_name}
                                    </div>

                                    <div class="global-search-subtitle">
                                        ${item.store_name} • Stock: ${item.quantity}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if(data.users.length > 0){

                        html += `
                            <div class="global-search-section-title">
                                Users
                            </div>
                        `;

                        data.users.forEach(item => {

                            html += `
                                <a
                                    href="/users/${item.id}/edit"
                                    class="global-search-item"
                                >
                                    <div class="global-search-title">
                                        ${item.name}
                                    </div>

                                    <div class="global-search-subtitle">
                                        ${item.email} • ${item.role}
                                    </div>
                                </a>
                            `;

                        });

                    }

                    if(html === ''){

                        html = `
                            <div class="global-search-empty">
                                No result found
                            </div>
                        `;

                    }

                    globalSearchDropdown.innerHTML = html;

                    globalSearchDropdown.classList.add('show');

                });

        });

        document.addEventListener('click', function(e){

            if(
                !globalSearchInput.contains(e.target)
                &&
                !globalSearchDropdown.contains(e.target)
            ){
                globalSearchDropdown.classList.remove('show');
            }

        });

        globalSearchInput.addEventListener('focus', function(){

            if(this.value.length >= 2){
                globalSearchDropdown.classList.add('show');
            }

        });

    }

</script>

<script>

    if (
        localStorage.getItem('darkMode')
        ===
        'enabled'
    ) {
        document.body.classList.add('dark-mode');
    }

    const darkToggle = document.getElementById('darkModeToggle');

    if (darkToggle) {

        darkToggle.addEventListener('click', () => {

            document.body.classList.toggle('dark-mode');

            if (
                document.body.classList.contains('dark-mode')
            ) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }

        });

    }

</script>

<script>

    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    window.addEventListener('load', function () {
        window.scrollTo(0, 0);
    });

</script>

</body>

</html>