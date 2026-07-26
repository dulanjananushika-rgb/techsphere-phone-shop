@extends('layouts.app')
@section('title', 'Invoice '.$order->invoice_number.' | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <article class="invoice">
            <div class="invoice-head">
                <div>
                    <div class="eyebrow">Customer invoice</div>
                    <h1 style="margin:6px 0">Invoice</h1>
                    <p class="muted">{{ $order->invoice_number }} | {{ $order->invoiced_at?->format('M d, Y') }}</p>
                </div>
                <div class="invoice-brand">
                    {{ $shopSettings['store_name'] }}<br>
                    <span>{{ $shopSettings['shop_address'] }}<br>{{ $shopSettings['shop_phone'] }}<br>{{ $shopSettings['shop_email'] }}</span>
                </div>
            </div>

            <div class="grid grid-2" style="margin-bottom:24px">
                <div>
                    <h3>Bill to</h3>
                    <p>{{ $order->customer_name }}<br>{{ $order->customer_phone }}<br>{{ $order->customer_email ?: 'Email not provided' }}</p>
                    @if($order->customer_address)<p>{{ $order->customer_address }}</p>@endif
                </div>
                <div>
                    <h3>Order details</h3>
                    <p>
                        {{ $order->order_number }}<br>
                        Status: {{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}<br>
                        Payment: {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}<br>
                        Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}<br>
                        Fulfilment: {{ ucfirst($order->delivery_method) }}
                    </p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Item</th><th>Qty</th><th>Unit</th><th>Discount</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td><strong>{{ $item->item_name }}</strong>@if($item->variant_name)<br><span class="muted">{{ $item->variant_name }}</span>@endif</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rs. {{ number_format($item->unit_price) }}</td>
                                <td>Rs. {{ number_format($item->discount_amount) }}</td>
                                <td>Rs. {{ number_format($item->line_total) }}</td>
                            </tr>
                        @endforeach
                        <tr><th colspan="4">Subtotal</th><td>Rs. {{ number_format($order->subtotal) }}</td></tr>
                        <tr><th colspan="4">Discount</th><td>- Rs. {{ number_format($order->discount_total) }}</td></tr>
                        <tr><th colspan="4">Delivery</th><td>Rs. {{ number_format($order->delivery_fee) }}</td></tr>
                        <tr><th colspan="4">Total</th><td><strong>Rs. {{ number_format($order->total) }}</strong></td></tr>
                    </tbody>
                </table>
            </div>
            <p class="field-help">Payment status: {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}. This invoice is proof of payment only when marked Paid.</p>
            <button class="btn btn-primary print-hide" type="button" onclick="window.print()" style="margin-top:18px">Print invoice</button>
        </article>
    </div>
</section>
@endsection
