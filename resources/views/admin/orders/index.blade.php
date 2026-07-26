@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="toolbar">
    <div><h1>Orders</h1><p class="muted">Track customer reservations and stock-impacting requests.</p></div>
</div>

<table class="table">
    <tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Payment</th><th></th></tr>
    @foreach($orders as $order)
        <tr>
            <td><strong>{{ $order->order_number }}</strong><br><span class="muted">{{ $order->created_at->format('M d, h:i A') }}</span></td>
            <td>{{ $order->customer_name }}<br><span class="muted">{{ $order->customer_phone }}</span></td>
            <td>{{ $order->items->sum('quantity') }} item(s)</td>
            <td>Rs. {{ number_format($order->total) }}</td>
            <td><span class="status-pill status-{{ $order->status === 'cancelled' ? 'expired' : 'active' }}">{{ $statuses[$order->status] ?? $order->status }}</span></td>
            <td>{{ $paymentStatuses[$order->payment_status] ?? $order->payment_status }}</td>
            <td><a class="btn btn-small" href="{{ route('admin.orders.show', $order) }}">Manage</a></td>
        </tr>
    @endforeach
</table>
<div style="margin-top:16px">{{ $orders->links() }}</div>
@endsection
