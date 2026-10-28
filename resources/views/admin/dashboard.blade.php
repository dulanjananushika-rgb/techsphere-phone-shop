@extends('layouts.admin')
@section('title', 'Executive Operations Dashboard')

@section('content')
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;flex-wrap:wrap;gap:16px">
    <div>
        <div class="eyebrow">Real-Time Metrics</div>
        <h1 style="font-size:2rem;margin:4px 0">Showroom Operations Console</h1>
        <p class="muted">Live overview of inventory, pending atomic reservations, and customer notifications.</p>
    </div>
    <div class="actions">
        <a class="btn btn-primary" href="{{ route('admin.orders.index') }}">
            <span>📦 Manage Active Orders</span>
        </a>
        <a class="btn" href="{{ route('admin.phones.create') }}">
            <span>➕ Add New Phone</span>
        </a>
    </div>
</div>

<!-- Glowing Metric Stat Cards -->
<div class="stat-grid">
    @foreach($stats as $label => $value)
        <div class="stat" style="position:relative;overflow:hidden">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <span>{{ $label === 'orders' ? '⚡ New Orders (Need Confirmation)' : ucfirst($label) }}</span>
                <span style="font-size:1.2rem">
                    @if($label === 'orders') 📥 @elseif($label === 'phones') 📱 @elseif($label === 'brands') 🏷️ @elseif($label === 'accessories') 🎧 @elseif($label === 'offers') ⚡ @else 👥 @endif
                </span>
            </div>
            <strong>{{ $value }}</strong>
        </div>
    @endforeach
</div>

<div class="grid grid-2">
    <!-- Active Smartphone Inventory -->
    <section class="card card-body">
        <div class="compact-toolbar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h2 style="font-size:1.25rem">Latest Catalog Additions</h2>
            <a class="btn btn-small" href="{{ route('admin.phones.index') }}">Full Inventory &rarr;</a>
        </div>
        <div class="table-wrap">
            <table class="table compact-table">
                <thead><tr><th>Smartphone</th><th>Brand</th><th>Price</th><th>Stock</th></tr></thead>
                <tbody>
                    @foreach($latestPhones as $phone)
                        <tr>
                            <td><strong style="color:#ffffff">{{ $phone->name }}</strong></td>
                            <td><span style="color:var(--titanium-gold);font-weight:700">{{ $phone->brand->name }}</span></td>
                            <td>Rs. {{ number_format($phone->price) }}</td>
                            <td>
                                <span class="status-pill status-shipped">{{ $phone->availableStock() }} available</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- Recent Order Inflow -->
    <section class="card card-body">
        <div class="compact-toolbar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h2 style="font-size:1.25rem">Recent Incoming Reservations</h2>
            <a class="btn btn-small" href="{{ route('admin.orders.index') }}">All Orders &rarr;</a>
        </div>
        <div class="table-wrap">
            <table class="table compact-table">
                <thead><tr><th>Order Ref</th><th>Recipient</th><th>Order State</th></tr></thead>
                <tbody>
                    @forelse($latestOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" style="color:var(--sapphire-light);font-family:monospace;font-weight:800">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td><span class="status-pill status-{{ $order->status }}">{{ \App\Models\Order::STATUSES[$order->status] ?? $order->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted">No orders received yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Communications & Email Audit -->
    <section class="card card-body" style="grid-column: 1 / -1">
        <div class="compact-toolbar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <h2 style="font-size:1.25rem">Real-Time Notification Audit Log</h2>
            <a class="btn btn-small" href="{{ route('admin.notifications.index') }}">Audit Log Center &rarr;</a>
        </div>
        <div class="table-wrap">
            <table class="table compact-table">
                <thead><tr><th>Notification Subject</th><th>Channel</th><th>Delivery Status</th><th>Timestamp</th></tr></thead>
                <tbody>
                    @forelse($latestNotifications as $notification)
                        <tr>
                            <td><strong style="color:#ffffff">{{ $notification->subject }}</strong></td>
                            <td><span style="font-size:0.8rem;text-transform:uppercase;color:var(--titanium-gold);font-weight:700">{{ $notification->channel }}</span></td>
                            <td><span class="status-pill status-{{ $notification->status }}">{{ ucfirst($notification->status) }}</span></td>
                            <td class="muted">{{ $notification->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted">No dispatch logs recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
