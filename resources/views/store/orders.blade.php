@extends('layouts.app')
@section('title', 'My Orders | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Your account</div>
                <h1>My orders</h1>
                <p>Track reservations, payment state, and fulfilment progress.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('phones.index') }}">Shop phones</a>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Order</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong><br><span class="muted">{{ $order->created_at->format('M d, Y') }}</span></td>
                            <td>
                                @foreach($order->items as $item)
                                    {{ $item->item_name }} x {{ $item->quantity }}@if(!$loop->last)<br>@endif
                                @endforeach
                            </td>
                            <td>Rs. {{ number_format($order->total) }}</td>
                            <td><span class="status-pill status-{{ $order->payment_status }}">{{ \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? $order->payment_status }}</span></td>
                            <td><span class="status-pill status-{{ $order->status }}">{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</span></td>
                            <td><a class="btn btn-small" href="{{ route('orders.success', $order->access_token) }}">View order</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">You have not placed an order yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $orders->links() }}</div>
    </div>
</section>
@endsection
