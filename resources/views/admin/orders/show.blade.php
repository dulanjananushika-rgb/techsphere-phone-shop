@extends('layouts.admin')
@section('title', $order->order_number)

@section('content')
<div class="toolbar">
    <div>
        <div class="eyebrow">Order management</div>
        <h1>{{ $order->order_number }}</h1>
        <p>{{ $order->customer_name }} | {{ $order->customer_phone }} | {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('admin.orders.index') }}">Back</a>
        <a class="btn btn-primary" href="{{ route('orders.invoice', $order->access_token) }}" target="_blank">Invoice</a>
    </div>
</div>

<div class="grid grid-2">
    <section class="card card-body">
        <h2>Items and totals</h2>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td><strong>{{ $item->item_name }}</strong><br><span class="muted">{{ $item->variant_name ?: ucfirst($item->item_type) }}</span></td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->line_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="checkout-total" style="margin-top:16px">
            <div><span>Subtotal</span><strong>Rs. {{ number_format($order->subtotal) }}</strong></div>
            <div><span>Discount</span><strong>- Rs. {{ number_format($order->discount_total) }}</strong></div>
            <div><span>Delivery</span><strong>Rs. {{ number_format($order->delivery_fee) }}</strong></div>
            <div class="total-row"><span>Total</span><strong>Rs. {{ number_format($order->total) }}</strong></div>
        </div>
    </section>

    <section class="card card-body">
        <h2>Customer and fulfilment</h2>
        <p><strong>Email:</strong> {{ $order->customer_email ?: 'Not provided' }}</p>
        <p><strong>Fulfilment:</strong> {{ ucfirst(str_replace('_', ' ', $order->delivery_method)) }}</p>
        <p><strong>Payment method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
        <p><strong>Payment reference:</strong> {{ $order->payment_reference ?: 'Not provided' }}</p>
        <p><strong>Address:</strong><br>{{ $order->customer_address ?: 'Showroom pickup' }}</p>
        <p><strong>Notes:</strong><br>{{ $order->notes ?: 'No notes' }}</p>
        @if($order->status === 'new' && $order->reserved_until)
            <p class="offer-meta">Reservation expires {{ $order->reserved_until->format('M d, Y h:i A') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.orders.update', $order) }}" data-lock-submit>
            @csrf
            @method('PATCH')
            <div class="grid grid-2">
                <div class="form-group">
                    <label for="manage-order-status">Order status</label>
                    <select id="manage-order-status" name="status">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="manage-payment-status">Payment status</label>
                    <select id="manage-payment-status" name="payment_status">
                        @foreach($paymentStatuses as $key => $label)
                            <option value="{{ $key }}" @selected($order->payment_status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="manage-payment-reference">Payment reference</label>
                <input id="manage-payment-reference" name="payment_reference" value="{{ old('payment_reference', $order->payment_reference) }}">
            </div>
            <button class="btn btn-primary" type="submit" data-loading-text="Updating...">Update order</button>
        </form>
    </section>
</div>
@endsection
