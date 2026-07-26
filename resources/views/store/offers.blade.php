@extends('layouts.app')
@section('title', 'Deals | TechSphere')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Shop promotions</div>
                <h2>Special Deals</h2>
                <p>Limited-time offers available for selected phones and accessories.</p>
            </div>
        </div>

        <div class="grid grid-2">
            @forelse($offers as $offer)
                <div class="offer-card offer-card-large">
                    <div class="offer-badge">{{ $offer->discount_percentage }}% off</div>
                    <h2>{{ $offer->title }}</h2>
                    <p>{{ $offer->description }}</p>
                    <div class="offer-meta">{{ $offer->starts_at->format('M d') }} - {{ $offer->ends_at->format('M d, Y') }}</div>
                    @if($offer->phones->count() || $offer->accessories->count())
                        <p class="muted">Applies to {{ $offer->phones->count() }} phone(s) and {{ $offer->accessories->count() }} accessory item(s).</p>
                    @endif
                    <a class="btn btn-primary" href="{{ route('phones.index') }}">Shop eligible products</a>
                </div>
            @empty
                <div class="empty-state">No active deals right now.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
