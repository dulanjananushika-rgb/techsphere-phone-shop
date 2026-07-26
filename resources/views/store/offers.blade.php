@extends('layouts.app')
@section('title', 'Current Deals | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Active promotions</div>
                <h1>Current deals</h1>
                <p>Only offers active today are shown. Discounts apply automatically at reservation.</p>
            </div>
        </div>

        <div class="offer-grid">
            @forelse($offers as $offer)
                <article class="offer-card">
                    <div class="offer-badge">Save {{ $offer->discount_percentage }}%</div>
                    <h2>{{ $offer->title }}</h2>
                    <p>{{ $offer->description }}</p>
                    <div class="offer-meta">{{ $offer->starts_at->format('M d') }} to {{ $offer->ends_at->format('M d, Y') }}</div>
                    <div class="offer-products">
                        @foreach($offer->phones as $phone)
                            <a href="{{ route('phones.show', $phone) }}">{{ $phone->name }}</a>
                        @endforeach
                        @foreach($offer->accessories as $accessory)
                            <a href="{{ route('orders.accessory', $accessory) }}">{{ $accessory->name }}</a>
                        @endforeach
                    </div>
                    @if($offer->phones->isNotEmpty())
                        <a class="btn btn-primary" href="{{ route('phones.index') }}">Browse eligible phones</a>
                    @elseif($offer->accessories->isNotEmpty())
                        <a class="btn btn-primary" href="{{ route('accessories.index') }}">Browse accessories</a>
                    @endif
                </article>
            @empty
                <div class="empty-state">There are no active deals today. Check back soon.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
