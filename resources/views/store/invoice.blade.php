@extends('layouts.app')
@section('title', 'Invoice '.$order->invoice_number.' | TechSphere')

@section('content')
<section class="section">
    <div class="container">
        <div class="invoice">
            <div class="invoice-head">
                <div>
                    <h1>Invoice</h1>
                    <p class="muted">{{ $order->invoice_number }} - {{ $order->invoiced_at?->format('M d, Y') }}</p>
                </div>
                <div class="invoice-brand">TechSphere Mobile<br><span>Colombo, Sri Lanka</span></div>
            </div>

            <div class="grid grid-2">
                <div>
                    <h3>Bill To</h3>
                    <p>{{ $order->customer_name }}<br>{{ $order->customer_phone }}<br>{{ $order->customer_email }}</p>
                </div>
                <div>
                    <h3>Order</h3>
                    <p>{{ $order->order_number }}<br>Payment: {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}<br>Delivery: {{ ucfirst($order->delivery_method) }}</p>
                </div>
            </div>

            <table class="compare-table">
                <tr><th>Item</th><th>Qty</th><th>Unit</th><th>Discount</th><th>Total</th></tr>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->item_name }} @if($item->variant_name)<br><span class="muted">{{ $item->variant_name }}</span>@endif</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs. {{ number_format($item->unit_price) }}</td>
                        <td>Rs. {{ number_format($item->discount_amount) }}</td>
                        <td>Rs. {{ number_format($item->line_total) }}</td>
                    </tr>
                @endforeach
                <tr><th colspan="4">Subtotal</th><td>Rs. {{ number_format($order->subtotal) }}</td></tr>
                <tr><th colspan="4">Discount</th><td>Rs. {{ number_format($order->discount_total) }}</td></tr>
                <tr><th colspan="4">Total</th><td><strong>Rs. {{ number_format($order->total) }}</strong></td></tr>
            </table>
            <button class="btn btn-primary" onclick="window.print()" style="margin-top:18px">Print invoice</button>
        </div>
    </div>
</section>
@endsection
