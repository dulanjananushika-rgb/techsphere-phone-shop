@extends('layouts.app')
@section('title', 'My Order History | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Customer Account Hub</div>
                <h1>Order & Reservation History</h1>
                <p>Track live reservation statuses, delivery tracking, and digital invoices.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('phones.index') }}">Browse New Phones &rarr;</a>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order Identifier</th>
                        <th>Hardware Items</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Order Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong style="color:var(--titanium-gold);font-family:monospace;font-size:1.05rem">{{ $order->order_number }}</strong><br>
                                <span class="muted" style="font-size:0.8rem">Placed on {{ $order->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <div>
                                        <strong style="color:#ffffff">{{ $item->item_name }}</strong> 
                                        <span class="muted">&times; {{ $item->quantity }}</span>
                                        @if($item->variant_name)<br><small class="muted">({{ $item->variant_name }})</small>@endif
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <strong style="font-size:1.1rem;color:#ffffff">Rs. {{ number_format($order->total) }}</strong>
                            </td>
                            <td>
                                <span class="status-pill status-{{ $order->payment_status }}">
                                    {{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}
                                </span>
                            </td>
                            <td>
                                <span class="status-pill status-{{ $order->status }}">
                                    {{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-small btn-primary" href="{{ route('orders.success', $order->access_token) }}">
                                    <span>Track Order</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state" style="padding:40px 20px;border:none;background:transparent">
                                    <p style="font-size:1.2rem;margin-bottom:8px">📦 No orders placed yet</p>
                                    <p class="muted">Reserve your first smartphone with zero advance charge.</p>
                                    <a class="btn btn-primary" style="margin-top:14px" href="{{ route('phones.index') }}">Start Shopping</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $orders->links() }}</div>
    </div>
</section>
@endsection
