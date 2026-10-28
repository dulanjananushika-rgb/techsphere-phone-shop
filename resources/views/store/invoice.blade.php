@extends('layouts.app')
@section('title', 'Official Invoice '.$order->invoice_number.' | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <article class="invoice" style="border:1px solid var(--border-glow);position:relative">
            <!-- Digital Verification Seal Badge -->
            <div class="print-hide" style="position:absolute;top:24px;right:24px;display:flex;align-items:center;gap:6px;padding:6px 14px;background:rgba(226,177,112,0.1);border:1px solid rgba(226,177,112,0.35);border-radius:var(--radius-full);font-size:0.75rem;font-weight:800;color:var(--titanium-gold);text-transform:uppercase">
                <span>🛡️ Verified Electronic Invoice</span>
            </div>

            <div class="invoice-head">
                <div>
                    <div class="eyebrow">Tax & Sales Invoice</div>
                    <h1 style="margin:4px 0 8px;font-size:2rem">{{ $shopSettings['store_name'] }}</h1>
                    <p class="muted" style="font-family:monospace;font-size:0.95rem">
                        Invoice No: <strong style="color:#ffffff">{{ $order->invoice_number }}</strong> &bull; Date: {{ $order->invoiced_at?->format('M d, Y') }}
                    </p>
                </div>
                <div class="invoice-brand" style="text-align:right">
                    <span style="display:block;font-size:1.1rem;font-weight:900;color:#ffffff;margin-bottom:4px">{{ $shopSettings['store_name'] }}</span>
                    <span>
                        {{ $shopSettings['shop_address'] }}<br>
                        Tel: {{ $shopSettings['shop_phone'] }}<br>
                        Email: {{ $shopSettings['shop_email'] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-2" style="margin-bottom:32px;gap:24px">
                <div style="background:rgba(255,255,255,0.02);padding:20px;border-radius:var(--radius-md);border:1px solid var(--border-subtle)">
                    <h3 style="font-size:0.85rem;text-transform:uppercase;color:var(--titanium-gold);letter-spacing:0.08em;margin-bottom:10px">Billed To Customer:</h3>
                    <p style="margin:0;line-height:1.6">
                        <strong style="color:#ffffff;font-size:1.05rem">{{ $order->customer_name }}</strong><br>
                        Phone: {{ $order->customer_phone }}<br>
                        Email: {{ $order->customer_email ?: 'Not provided' }}
                    </p>
                    @if($order->customer_address)
                        <p style="margin-top:8px;font-size:0.88rem;color:var(--text-secondary)">Delivery: {{ $order->customer_address }}</p>
                    @endif
                </div>

                <div style="background:rgba(255,255,255,0.02);padding:20px;border-radius:var(--radius-md);border:1px solid var(--border-subtle)">
                    <h3 style="font-size:0.85rem;text-transform:uppercase;color:var(--titanium-gold);letter-spacing:0.08em;margin-bottom:10px">Transaction Metadata:</h3>
                    <p style="margin:0;line-height:1.6">
                        Order Code: <strong style="color:#ffffff;font-family:monospace">{{ $order->order_number }}</strong><br>
                        Order State: <span class="status-pill status-{{ $order->status }}">{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</span><br>
                        Payment Status: <span class="status-pill status-{{ $order->payment_status }}">{{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}</span><br>
                        Payment Method: <strong>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</strong><br>
                        Fulfilment: <strong>{{ ucfirst($order->delivery_method) }}</strong>
                    </p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Hardware Item Description</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:right">Unit Price</th>
                            <th style="text-align:right">Discount</th>
                            <th style="text-align:right">Net Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong style="color:#ffffff">{{ $item->item_name }}</strong>
                                    @if($item->variant_name)<br><span class="muted" style="font-size:0.84rem">Spec: {{ $item->variant_name }}</span>@endif
                                </td>
                                <td style="text-align:center;font-weight:700">{{ $item->quantity }}</td>
                                <td style="text-align:right">Rs. {{ number_format($item->unit_price) }}</td>
                                <td style="text-align:right;color:var(--rose)">- Rs. {{ number_format($item->discount_amount) }}</td>
                                <td style="text-align:right;color:#ffffff;font-weight:700">Rs. {{ number_format($item->line_total) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4" style="text-align:right">Subtotal</th>
                            <td style="text-align:right;font-weight:700">Rs. {{ number_format($order->subtotal) }}</td>
                        </tr>
                        @if($order->discount_total > 0)
                            <tr>
                                <th colspan="4" style="text-align:right;color:var(--rose)">Promotional Savings</th>
                                <td style="text-align:right;color:var(--rose);font-weight:700">- Rs. {{ number_format($order->discount_total) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th colspan="4" style="text-align:right">Fulfilment & Delivery Fee</th>
                            <td style="text-align:right;font-weight:700">Rs. {{ number_format($order->delivery_fee) }}</td>
                        </tr>
                        <tr style="background:rgba(99,102,241,0.08)">
                            <th colspan="4" style="text-align:right;font-size:1.1rem;color:#ffffff">Total Invoice Amount</th>
                            <td style="text-align:right;font-size:1.35rem;font-weight:900;color:var(--sapphire-light)">
                                <strong>Rs. {{ number_format($order->total) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="margin-top:24px;padding:16px;background:rgba(255,255,255,0.02);border-radius:var(--radius-sm);font-size:0.82rem;color:var(--text-secondary);line-height:1.6">
                <strong>Warranty & Terms:</strong> All devices carry manufacturer warranty or showroom replacement assistance as stated on the warranty card. Proof of payment is valid upon receipt of funds.
            </div>

            <div class="print-hide" style="margin-top:28px;display:flex;gap:12px;justify-content:flex-end">
                <a class="btn" href="{{ route('orders.success', $order->access_token) }}">&larr; Back to Tracking</a>
                <button class="btn btn-primary" type="button" onclick="window.print()">
                    <span>🖨️ Print / Save as PDF</span>
                </button>
            </div>
        </article>
    </div>
</section>
@endsection
