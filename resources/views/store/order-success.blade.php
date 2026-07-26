@extends('layouts.app')
@section('title', 'Order Sent | TechSphere')

@section('content')
<section class="section">
    <div class="container">
        <div class="card card-body" style="max-width:720px;margin:0 auto">
            <div class="eyebrow">Order received</div>
            <h1>{{ $order->order_number }}</h1>
            <p class="muted">Thanks {{ $order->customer_name }}. We have reserved the item and will contact you on {{ $order->customer_phone }}.</p>
            <p><strong>Invoice:</strong> {{ $order->invoice_number }} - <strong>Payment:</strong> {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}</p>
            <table class="compare-table">
                <tr><th>Item</th><th>Qty</th><th>Total</th></tr>
                @foreach($order->items as $item)
                    <tr><td>{{ $item->item_name }} @if($item->variant_name)<br><span class="muted">{{ $item->variant_name }}</span>@endif</td><td>{{ $item->quantity }}</td><td>Rs. {{ number_format($item->line_total) }}</td></tr>
                @endforeach
                <tr><th colspan="2">Order Total</th><th>Rs. {{ number_format($order->total) }}</th></tr>
            </table>
            <div class="actions" style="margin-top:18px">
                <a class="btn btn-primary" href="{{ route('phones.index') }}">Continue shopping</a>
                <a class="btn" href="{{ route('orders.invoice', $order) }}">View invoice</a>
                <a class="btn btn-green" target="_blank" href="https://wa.me/94771234567?text={{ urlencode('Hi TechSphere, I submitted order '.$order->order_number) }}">Message on WhatsApp</a>
            </div>
        </div>
    </div>
</section>
@endsection
