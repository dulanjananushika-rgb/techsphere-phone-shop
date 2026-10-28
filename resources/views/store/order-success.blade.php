@extends('layouts.app')
@section('title', 'Order Confirmed '.$order->order_number.' | '.$shopSettings['store_name'])

@section('content')
@php
    $steps = ['new', 'confirmed', 'processing', $order->delivery_method === 'delivery' ? 'shipped' : 'ready', 'delivered'];
    $currentStep = array_search($order->status, $steps, true);
@endphp
<section class="section">
    <div class="container">
        <div class="card card-body" style="max-width:820px;margin:0 auto;padding:42px">
            <div class="eyebrow">Atomic Stock Lock Activated</div>
            
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin:12px 0 24px;flex-wrap:wrap;gap:16px">
                <div>
                    <h1 style="font-size:2.2rem;font-family:monospace;letter-spacing:-0.03em;color:var(--titanium-gold);margin-bottom:6px">{{ $order->order_number }}</h1>
                    <p class="muted">Reservation Placed: {{ $order->created_at->format('M d, Y &bull; h:i A') }}</p>
                </div>
                <span class="status-pill status-{{ $order->status }}" style="font-size:0.85rem;padding:6px 16px">
                    {{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}
                </span>
            </div>

            @if($order->status === 'new' && $order->reserved_until)
                <div class="notice" style="margin-bottom:28px">
                    <span>Stock serial is reserved until <strong>{{ $order->reserved_until->format('M d, Y h:i A') }}</strong>. Our team will verify and prepare your package.</span>
                </div>
            @endif

            <!-- Next-Gen Animated Progress Timeline -->
            @if($order->status !== 'cancelled')
                <div style="margin:28px 0">
                    <div class="order-timeline" aria-label="Order progress">
                        @foreach($steps as $index => $step)
                            <span @class(['timeline-step', 'done' => $currentStep !== false && $index <= $currentStep]) title="{{ \App\Models\Order::STATUSES[$step] ?? $step }}"></span>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.75rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.06em">
                        <span>New</span>
                        <span>Confirmed</span>
                        <span>Preparing</span>
                        <span>{{ $order->delivery_method === 'delivery' ? 'Dispatched' : 'Ready' }}</span>
                        <span>Delivered</span>
                    </div>
                </div>
            @endif

            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:18px;margin:24px 0;line-height:1.7">
                Thank you, <strong>{{ $order->customer_name }}</strong>. We will reach out to you on <strong style="color:var(--sapphire-light)">{{ $order->customer_phone }}</strong> to confirm payment and arrange {{ $order->delivery_method === 'delivery' ? 'doorstep delivery' : 'showroom collection' }}.
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Reserved Item</th><th style="text-align:center">Qty</th><th style="text-align:right">Line Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong style="color:#ffffff">{{ $item->item_name }}</strong>
                                    @if($item->variant_name)<br><span class="muted" style="font-size:0.84rem">Tier: {{ $item->variant_name }}</span>@endif
                                </td>
                                <td style="text-align:center">{{ $item->quantity }}</td>
                                <td style="text-align:right;color:var(--sapphire-light);font-weight:700">Rs. {{ number_format($item->line_total) }}</td>
                            </tr>
                        @endforeach
                        @if($order->delivery_fee > 0)
                            <tr>
                                <td colspan="2" style="color:var(--text-secondary)">Islandwide Delivery Fee</td>
                                <td style="text-align:right">Rs. {{ number_format($order->delivery_fee) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th colspan="2" style="font-size:1.1rem;color:#ffffff">Total Payable</th>
                            <th style="text-align:right;font-size:1.3rem;color:var(--sapphire-light)">Rs. {{ number_format($order->total) }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="actions" style="margin-top:28px;display:flex;flex-wrap:wrap;gap:12px">
                <a class="btn btn-primary" href="{{ route('orders.invoice', $order->access_token) }}">
                    <span>📄 View & Print Invoice</span>
                </a>
                @auth
                    <a class="btn" href="{{ route('orders.index') }}">My Order History</a>
                @else
                    <a class="btn" href="{{ route('phones.index') }}">Continue Shopping</a>
                @endauth
                <a class="btn btn-green" target="_blank" rel="noopener" href="https://wa.me/{{ $shopSettings['whatsapp_number'] }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I just placed order '.$order->order_number.' for Rs. '.number_format($order->total).'.') }}">
                    <span>💬 WhatsApp Support</span>
                </a>
            </div>

            <p class="field-help" style="margin-top:20px">
                🔒 Security Note: Bookmark this private URL to access your real-time tracking page and invoice anytime.
            </p>
        </div>
    </div>
</section>
@endsection
