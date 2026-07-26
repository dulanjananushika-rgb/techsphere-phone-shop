@extends('layouts.app')
@section('title', 'Order '.$order->order_number.' | '.$shopSettings['store_name'])

@section('content')
@php
    $steps = ['new', 'confirmed', 'processing', $order->delivery_method === 'delivery' ? 'shipped' : 'ready', 'delivered'];
    $currentStep = array_search($order->status, $steps, true);
@endphp
<section class="section">
    <div class="container">
        <div class="card card-body" style="max-width:780px;margin:0 auto">
            <div class="eyebrow">Reservation received</div>
            <div class="toolbar" style="margin-top:6px">
                <div>
                    <h1>{{ $order->order_number }}</h1>
                    <p class="muted">Placed {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <span class="status-pill status-{{ $order->status }}">{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</span>
            </div>

            @if($order->status === 'new' && $order->reserved_until)
                <div class="notice">Stock is held until {{ $order->reserved_until->format('M d, Y h:i A') }} while the shop confirms your request.</div>
            @endif

            @if($order->status !== 'cancelled')
                <div class="order-timeline" aria-label="Order progress">
                    @foreach($steps as $index => $step)
                        <span @class(['timeline-step', 'done' => $currentStep !== false && $index <= $currentStep]) title="{{ \App\Models\Order::STATUSES[$step] ?? $step }}"></span>
                    @endforeach
                </div>
            @endif

            <p>Thanks, <strong>{{ $order->customer_name }}</strong>. We will contact you on {{ $order->customer_phone }} to confirm payment and {{ $order->delivery_method === 'delivery' ? 'delivery' : 'pickup' }}.</p>

            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Item</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td><strong>{{ $item->item_name }}</strong>@if($item->variant_name)<br><span class="muted">{{ $item->variant_name }}</span>@endif</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rs. {{ number_format($item->line_total) }}</td>
                            </tr>
                        @endforeach
                        @if($order->delivery_fee > 0)
                            <tr><td colspan="2">Delivery</td><td>Rs. {{ number_format($order->delivery_fee) }}</td></tr>
                        @endif
                        <tr><th colspan="2">Order total</th><th>Rs. {{ number_format($order->total) }}</th></tr>
                    </tbody>
                </table>
            </div>

            <div class="actions" style="margin-top:20px">
                <a class="btn btn-primary" href="{{ route('orders.invoice', $order->access_token) }}">View invoice</a>
                @auth
                    <a class="btn" href="{{ route('orders.index') }}">My orders</a>
                @else
                    <a class="btn" href="{{ route('phones.index') }}">Continue shopping</a>
                @endauth
                <a class="btn btn-green" target="_blank" rel="noopener" href="https://wa.me/{{ $shopSettings['whatsapp_number'] }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I submitted order '.$order->order_number.'.') }}">Message the shop</a>
            </div>
            <p class="field-help">Keep this private link if you checked out as a guest. It opens your order and invoice.</p>
        </div>
    </div>
</section>
@endsection
