@extends('layouts.admin')
@section('title', $order->order_number)

@section('content')
<div class="toolbar">
    <div><h1>{{ $order->order_number }}</h1><p class="muted">{{ $order->customer_name }} - {{ $order->customer_phone }}</p></div>
    <a class="btn" href="{{ route('admin.orders.index') }}">Back to orders</a>
</div>

<div class="grid grid-2">
    <div class="card card-body">
        <h2>Order Items</h2>
        <table class="table">
            <tr><th>Item</th><th>Qty</th><th>Line Total</th></tr>
            @foreach($order->items as $item)
                <tr><td>{{ $item->item_name }}<br><span class="muted">{{ $item->variant_name ? $item->variant_name.' - ' : '' }}{{ ucfirst($item->item_type) }}</span></td><td>{{ $item->quantity }}</td><td>Rs. {{ number_format($item->line_total) }}</td></tr>
            @endforeach
        </table>
        <p><strong>Subtotal:</strong> Rs. {{ number_format($order->subtotal) }}</p>
        <p><strong>Discount:</strong> Rs. {{ number_format($order->discount_total) }}</p>
        <p><strong>Total:</strong> Rs. {{ number_format($order->total) }}</p>
    </div>

    <div class="card card-body">
        <h2>Customer and Status</h2>
        @if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
        <p><strong>Email:</strong> {{ $order->customer_email ?: 'Not provided' }}</p>
        <p><strong>Delivery:</strong> {{ ucfirst(str_replace('_', ' ', $order->delivery_method)) }}</p>
        <p><strong>Payment:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
        <p><strong>Address:</strong><br>{{ $order->customer_address ?: 'Pickup order' }}</p>
        <p><strong>Notes:</strong><br>{{ $order->notes ?: 'No notes' }}</p>

        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf @method('PATCH')
            <div class="grid grid-2">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment status</label>
                    <select name="payment_status">
                        @foreach($paymentStatuses as $key => $label)
                            <option value="{{ $key }}" @selected($order->payment_status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group"><label>Payment reference</label><input name="payment_reference" value="{{ old('payment_reference', $order->payment_reference) }}"></div>
            <button class="btn btn-primary">Update status</button>
        </form>
        <div class="actions" style="margin-top:14px">
            <a class="btn" href="{{ route('orders.invoice', $order) }}" target="_blank">View invoice</a>
        </div>
    </div>
</div>
@endsection
