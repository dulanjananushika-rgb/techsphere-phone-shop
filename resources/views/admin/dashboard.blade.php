@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="toolbar">
    <div><h1>Dashboard</h1><p class="muted">Live overview of catalog and content.</p></div>
    <div class="actions">
        <a class="btn btn-primary" href="{{ route('admin.orders.index') }}">View orders</a>
        <a class="btn" href="{{ route('admin.phones.create') }}">Add phone</a>
    </div>
</div>
<div class="stat-grid">
    @foreach($stats as $label => $value)
        <div class="stat"><span class="muted">{{ ucfirst($label) }}</span><strong>{{ $value }}</strong></div>
    @endforeach
</div>
<div class="grid grid-2">
<div class="card card-body">
    <h2>Latest Phones</h2>
    <table class="table">
        <tr><th>Phone</th><th>Brand</th><th>Price</th><th>Stock</th></tr>
        @foreach($latestPhones as $phone)
            <tr><td>{{ $phone->name }}</td><td>{{ $phone->brand->name }}</td><td>Rs. {{ number_format($phone->price) }}</td><td>{{ $phone->stock }}</td></tr>
        @endforeach
    </table>
</div>
<div class="card card-body">
    <h2>Recent Orders</h2>
    <table class="table">
        <tr><th>Order</th><th>Customer</th><th>Status</th></tr>
        @forelse($latestOrders as $order)
            <tr><td>{{ $order->order_number }}</td><td>{{ $order->customer_name }}</td><td>{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</td></tr>
        @empty
            <tr><td colspan="3">No orders yet.</td></tr>
        @endforelse
    </table>
</div>
<div class="card card-body">
    <div class="toolbar compact-toolbar">
        <div><h2>Notification Queue</h2></div>
        <a class="btn btn-small" href="{{ route('admin.notifications.index') }}">View all</a>
    </div>
    <table class="table">
        <tr><th>Subject</th><th>Channel</th><th>Status</th></tr>
        @forelse($latestNotifications as $notification)
            <tr><td>{{ $notification->subject }}</td><td>{{ ucfirst($notification->channel) }}</td><td>{{ ucfirst($notification->status) }}</td></tr>
        @empty
            <tr><td colspan="3">No notifications yet.</td></tr>
        @endforelse
    </table>
</div>
</div>
@endsection
