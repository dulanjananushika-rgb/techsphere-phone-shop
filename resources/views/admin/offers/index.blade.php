@extends('layouts.admin')
@section('title', 'Offers')
@section('content')
<div class="toolbar">
    <div>
        <h1>Special Offers</h1>
        <p class="muted">Create customer-facing deals for the home page and Deals section.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.offers.create') }}">Create offer</a>
</div>
<table class="table">
    <tr><th>Campaign</th><th>Discount</th><th>Applies To</th><th>Schedule</th><th>Status</th><th></th></tr>
    @foreach($offers as $offer)
        @php
            $status = now()->lt($offer->starts_at) ? 'upcoming' : (now()->gt($offer->ends_at) ? 'expired' : 'active');
        @endphp
        <tr>
            <td><strong>{{ $offer->title }}</strong><br><span class="muted">{{ \Illuminate\Support\Str::limit($offer->description, 72) }}</span></td>
            <td><strong>{{ $offer->discount_percentage }}%</strong></td>
            <td>{{ $offer->phones_count }} phones<br><span class="muted">{{ $offer->accessories_count }} accessories</span></td>
            <td>{{ $offer->starts_at->format('M d') }} - {{ $offer->ends_at->format('M d, Y') }}</td>
            <td><span class="status-pill status-{{ $status }}">{{ ucfirst($status) }}</span></td>
            <td>
                <a class="btn btn-small" href="{{ route('admin.offers.edit', $offer) }}">Edit</a>
                <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" style="display:inline">@csrf @method('DELETE')<button class="btn btn-danger btn-small">Delete</button></form>
            </td>
        </tr>
    @endforeach
</table>
<div style="margin-top:16px">{{ $offers->links() }}</div>
@endsection
