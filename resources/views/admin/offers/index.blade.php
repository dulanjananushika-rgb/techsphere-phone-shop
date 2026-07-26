@extends('layouts.admin')
@section('title', 'Special Offers')

@section('content')
<div class="toolbar">
    <div><h1>Special offers</h1><p>Schedule discounts for selected phones and accessories.</p></div>
    <a class="btn btn-primary" href="{{ route('admin.offers.create') }}">Create offer</a>
</div>
<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Campaign</th><th>Discount</th><th>Products</th><th>Schedule</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
            @foreach($offers as $offer)
                <tr>
                    <td><strong>{{ $offer->title }}</strong><br><span class="muted">{{ Str::limit($offer->description, 72) }}</span></td>
                    <td><strong>{{ $offer->discount_percentage }}%</strong></td>
                    <td>{{ $offer->phones_count }} phones<br><span class="muted">{{ $offer->accessories_count }} accessories</span></td>
                    <td>{{ $offer->starts_at->format('M d') }} to {{ $offer->ends_at->format('M d, Y') }}</td>
                    <td><span class="status-pill status-{{ $offer->status }}">{{ ucfirst($offer->status) }}</span></td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-small" href="{{ route('admin.offers.edit', $offer) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.offers.destroy', $offer) }}" data-confirm="Delete this offer permanently?">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-small" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="pagination">{{ $offers->links() }}</div>
@endsection
