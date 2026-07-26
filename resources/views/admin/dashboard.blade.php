@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="toolbar">
    <div>
        <h1>Dashboard</h1>
        <p>Today at a glance: orders, catalog, customers, and communication.</p>
    </div>
    <div class="actions">
        <a class="btn btn-primary" href="{{ route('admin.orders.index') }}">Manage orders</a>
        <a class="btn" href="{{ route('admin.phones.create') }}">Add phone</a>
    </div>
</div>

<div class="stat-grid">
    @foreach($stats as $label => $value)
        <div class="stat">
            <span class="muted">{{ $label === 'orders' ? 'Awaiting confirmation' : ucfirst($label) }}</span>
            <strong>{{ $value }}</strong>
        </div>
    @endforeach
</div>

<div class="grid grid-2">
    <section class="card card-body">
        <div class="compact-toolbar">
            <h2>Latest phones</h2>
            <a class="btn btn-small" href="{{ route('admin.phones.index') }}">View catalog</a>
        </div>
        <div class="table-wrap">
            <table class="table compact-table">
                <thead><tr><th>Phone</th><th>Brand</th><th>Price</th><th>Available</th></tr></thead>
                <tbody>
                    @foreach($latestPhones as $phone)
                        <tr>
                            <td>{{ $phone->name }}</td>
                            <td>{{ $phone->brand->name }}</td>
                            <td>Rs. {{ number_format($phone->price) }}</td>
                            <td>{{ $phone->availableStock() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="card card-body">
        <div class="compact-toolbar">
            <h2>Recent orders</h2>
            <a class="btn btn-small" href="{{ route('admin.orders.index') }}">View orders</a>
        </div>
        <div class="table-wrap">
            <table class="table compact-table">
                <thead><tr><th>Order</th><th>Customer</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($latestOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}"><strong>{{ $order->order_number }}</strong></a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td><span class="status-pill status-{{ $order->status }}">{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card card-body">
        <div class="compact-toolbar">
            <h2>Notification delivery</h2>
            <a class="btn btn-small" href="{{ route('admin.notifications.index') }}">View all</a>
        </div>
        <div class="table-wrap">
            <table class="table compact-table">
                <thead><tr><th>Subject</th><th>Channel</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($latestNotifications as $notification)
                        <tr>
                            <td>{{ $notification->subject }}</td>
                            <td>{{ ucfirst($notification->channel) }}</td>
                            <td><span class="status-pill status-{{ $notification->status }}">{{ ucfirst($notification->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No notifications yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
