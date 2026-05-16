@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="fw-bold">
                Order Detail
            </h1>

            <p class="text-muted mb-0">
                Order information, items, and status timeline
            </p>
        </div>

        <a
            href="/orders"
            class="btn btn-secondary"
        >
            Back to Orders
        </a>

    </div>

    <div class="row mb-4">

        <div class="col-md-7 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <div>
                            <h4 class="fw-bold mb-1">
                                Order #{{ $order->order_id }}
                            </h4>

                            <p class="text-muted mb-0">
                                {{ $order->order_date }}
                            </p>
                        </div>

                        @if($order->status == 'Pending')

                            <span class="badge bg-warning text-dark p-2">
                                Pending
                            </span>

                        @elseif($order->status == 'Paid')

                            <span class="badge bg-primary p-2">
                                Paid
                            </span>

                        @elseif($order->status == 'Shipped')

                            <span class="badge bg-info text-dark p-2">
                                Shipped
                            </span>

                        @elseif($order->status == 'Completed')

                            <span class="badge bg-success p-2">
                                Completed
                            </span>

                        @elseif($order->status == 'Cancelled')

                            <span class="badge bg-danger p-2">
                                Cancelled
                            </span>

                        @else

                            <span class="badge bg-secondary p-2">
                                {{ $order->status }}
                            </span>

                        @endif

                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Customer
                            </small>

                            <div class="fw-semibold">
                                {{ $order->customer_name }}
                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Email
                            </small>

                            <div>
                                {{ $order->email ?? '-' }}
                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Phone
                            </small>

                            <div>
                                {{ $order->phone ?? '-' }}
                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <small class="text-muted">
                                Grand Total
                            </small>

                            <div class="fw-bold">
                                {{ setting('currency', 'Rp') }}
                                {{ number_format($grandTotal, 0, ',', '.') }}
                            </div>

                        </div>

                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">

                        @if($order->status == 'Pending')

                            <form
                                action="/orders/{{ $order->order_id }}/pay"
                                method="POST"
                            >
                                @csrf

                                <button class="btn btn-success">
                                    Mark as Paid
                                </button>
                            </form>

                            <form
                                action="/orders/{{ $order->order_id }}/cancel"
                                method="POST"
                            >
                                @csrf

                                <button class="btn btn-danger">
                                    Cancel Order
                                </button>
                            </form>

                        @elseif($order->status == 'Paid')

                            <form
                                action="/orders/{{ $order->order_id }}/ship"
                                method="POST"
                            >
                                @csrf

                                <button class="btn btn-info text-dark">
                                    Mark as Shipped
                                </button>
                            </form>

                        @elseif($order->status == 'Shipped')

                            <form
                                action="/orders/{{ $order->order_id }}/complete"
                                method="POST"
                            >
                                @csrf

                                <button class="btn btn-success">
                                    Mark as Completed
                                </button>
                            </form>

                        @endif

                        <a
                            href="/orders/{{ $order->order_id }}/invoice-preview"
                            class="btn btn-outline-primary"
                        >
                            Invoice Preview
                        </a>

                        <a
                            href="/orders/{{ $order->order_id }}/invoice"
                            class="btn btn-outline-dark"
                        >
                            Download Invoice
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-5 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        Order Status Timeline
                    </h5>

                    @php
                        $timelineMap = $timelines->keyBy('status');

                        $steps = [
                            'Pending' => [
                                'title' => 'Order Created',
                                'icon' => 'bi-receipt',
                            ],
                            'Paid' => [
                                'title' => 'Order Paid',
                                'icon' => 'bi-credit-card',
                            ],
                            'Shipped' => [
                                'title' => 'Order Shipped',
                                'icon' => 'bi-truck',
                            ],
                            'Completed' => [
                                'title' => 'Order Completed',
                                'icon' => 'bi-check-circle',
                            ],
                        ];

                        $statusOrder = [
                            'Pending' => 1,
                            'Paid' => 2,
                            'Shipped' => 3,
                            'Completed' => 4,
                            'Cancelled' => 99,
                        ];

                        $currentLevel = $statusOrder[$order->status] ?? 1;
                    @endphp

                    @if($order->status == 'Cancelled')

                        <div class="timeline-item active danger">

                            <div class="timeline-icon">
                                <i class="bi bi-x-circle"></i>
                            </div>

                            <div class="timeline-content">

                                <h6>
                                    Order Cancelled
                                </h6>

                                @if(isset($timelineMap['Cancelled']))

                                    <small class="text-muted">
                                        {{ $timelineMap['Cancelled']->created_at->format('Y-m-d H:i') }}
                                        by
                                        {{ $timelineMap['Cancelled']->user_name ?? 'System' }}
                                    </small>

                                    <p>
                                        {{ $timelineMap['Cancelled']->description }}
                                    </p>

                                @else

                                    <small class="text-muted">
                                        This order has been cancelled.
                                    </small>

                                @endif

                            </div>

                        </div>

                    @else

                        @foreach($steps as $status => $step)

                            @php
                                $level = $statusOrder[$status];
                                $isActive = $currentLevel >= $level;
                                $timeline = $timelineMap[$status] ?? null;
                            @endphp

                            <div class="timeline-item {{ $isActive ? 'active' : '' }}">

                                <div class="timeline-icon">
                                    <i class="bi {{ $step['icon'] }}"></i>
                                </div>

                                <div class="timeline-content">

                                    <h6>
                                        {{ $step['title'] }}
                                    </h6>

                                    @if($timeline)

                                        <small class="text-muted">
                                            {{ $timeline->created_at->format('Y-m-d H:i') }}
                                            by
                                            {{ $timeline->user_name ?? 'System' }}
                                        </small>

                                        <p>
                                            {{ $timeline->description }}
                                        </p>

                                    @else

                                        <small class="text-muted">
                                            Waiting for this step
                                        </small>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Order Items
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Subtotal</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($items as $item)

                            <tr>
                                <td>{{ $item->product_name }}</td>

                                <td>{{ $item->quantity }}</td>

                                <td>
                                    {{ setting('currency', 'Rp') }}
                                    {{ number_format($item->list_price, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $item->discount }}%
                                </td>

                                <td>
                                    {{ setting('currency', 'Rp') }}
                                    {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="text-end mt-3">

                <h3 class="fw-bold">
                    Grand Total:
                    {{ setting('currency', 'Rp') }}
                    {{ number_format($grandTotal, 0, ',', '.') }}
                </h3>

            </div>

        </div>

    </div>

</div>

<style>
    .timeline-item {
        display: flex;
        gap: 14px;
        position: relative;
        padding-bottom: 26px;
        opacity: 0.45;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 42px;
        width: 2px;
        height: calc(100% - 30px);
        background: #d1d5db;
    }

    .timeline-item.active {
        opacity: 1;
    }

    .timeline-item.active:not(:last-child)::before {
        background: #2563eb;
    }

    .timeline-item.danger:not(:last-child)::before {
        background: #dc2626;
    }

    .timeline-icon {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        background: #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
        z-index: 1;
    }

    .timeline-item.active .timeline-icon {
        background: #2563eb;
        color: white;
    }

    .timeline-item.danger .timeline-icon {
        background: #dc2626;
        color: white;
    }

    .timeline-content h6 {
        font-weight: 800;
        margin-bottom: 4px;
    }

    .timeline-content p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    body.dark-mode .timeline-content p {
        color: #d1d5db;
    }

    body.dark-mode .timeline-icon {
        background: #374151;
        color: #d1d5db;
    }

    body.dark-mode .timeline-item.active .timeline-icon {
        background: #2563eb;
        color: white;
    }
</style>

@endsection