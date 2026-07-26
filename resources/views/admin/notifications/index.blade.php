@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')
<div class="toolbar">
    <div><h1>Notifications</h1><p class="muted">Track customer/admin notification events created by orders and status changes.</p></div>
</div>

<table class="table">
    <tr><th>Subject</th><th>Order</th><th>Channel</th><th>Recipient</th><th>Status</th><th>Sent At</th><th></th></tr>
    @forelse($notifications as $notification)
        <tr>
            <td><strong>{{ $notification->subject }}</strong><br><span class="muted">{{ $notification->message }}</span></td>
            <td>
                @if($notification->order)
                    <a href="{{ route('admin.orders.show', $notification->order) }}">{{ $notification->order->order_number }}</a>
                @else
                    <span class="muted">No order</span>
                @endif
            </td>
            <td>{{ ucfirst($notification->channel) }}</td>
            <td>{{ $notification->recipient }}</td>
            <td>
                <form method="POST" action="{{ route('admin.notifications.update', $notification) }}">
                    @csrf @method('PATCH')
                    <select name="status" onchange="this.form.submit()">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" @selected($notification->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td>{{ $notification->sent_at?->format('M d, h:i A') ?: 'Not sent' }}</td>
            <td>
                <form method="POST" action="{{ route('admin.notifications.destroy', $notification) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-small">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7">No notifications yet.</td></tr>
    @endforelse
</table>
<div style="margin-top:16px">{{ $notifications->links() }}</div>
@endsection
