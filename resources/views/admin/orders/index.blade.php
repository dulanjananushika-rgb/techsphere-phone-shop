@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="toolbar">
    <div>
        <h1>Orders</h1>
        <p>Confirm reservations, track payment, and manage fulfilment.</p>
    </div>
</div>

<form class="admin-filters" method="GET">
    <div class="form-group">
        <label for="order-search">Search</label>
        <input id="order-search" name="search" value="{{ request('search') }}" placeholder="Order number, customer, phone">
    </div>
    <div class="form-group">
        <label for="order-status">Order status</label>
        <select id="order-status" name="status">
            <option value="">All statuses</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="payment-status">Payment status</label>
        <select id="payment-status" name="payment_status">
            <option value="">All payments</option>
            @foreach($paymentStatuses as $key => $label)
                <option value="{{ $key }}" @selected(request('payment_status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="actions" style="align-self:end">
        <button class="btn btn-primary" type="submit">Filter</button>
        <a class="btn" href="{{ route('admin.orders.index') }}">Clear</a>
    </div>
</form>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Payment</th><th>Action</th></tr></thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong><br><span class="muted">{{ $order->created_at->format('M d, h:i A') }}</span></td>
                    <td>{{ $order->customer_name }}<br><span class="muted">{{ $order->customer_phone }}</span></td>
                    <td>{{ $order->items->sum('quantity') }} {{ Str::plural('item', $order->items->sum('quantity')) }}</td>
                    <td>Rs. {{ number_format($order->total) }}</td>
                    <td><span class="status-pill status-{{ $order->status }}">{{ $statuses[$order->status] ?? $order->status }}</span></td>
                    <td><span class="status-pill status-{{ $order->payment_status }}">{{ $paymentStatuses[$order->payment_status] ?? $order->payment_status }}</span></td>
                    <td><a class="btn btn-primary btn-small" href="{{ route('admin.orders.show', $order) }}">Manage</a></td>
                </tr>
            @empty
                <tr><td colspan="7">No orders match these filters.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $orders->links() }}</div>
@endsection
