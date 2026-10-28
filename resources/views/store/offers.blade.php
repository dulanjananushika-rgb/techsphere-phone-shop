@extends('layouts.app')
@section('title', 'Exclusive Promotional Deals | '.$shopSettings['store_name'])
@section('meta_description', 'Active discount events and product bundles with verified stock and instant savings.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Limited Period Promos</div>
                <h1>Exclusive Hardware Deals</h1>
                <p>Date-verified promotional discounts automatically deducted at checkout.</p>
            </div>
        </div>

        <div class="offer-grid">
            @forelse($offers as $offer)
                <article class="offer-card">
                    <div class="offer-badge">⚡ Instant {{ $offer->discount_percentage }}% Savings</div>
                    <h2>{{ $offer->title }}</h2>
                    <p class="muted" style="line-height:1.7">{{ $offer->description }}</p>
                    <div class="offer-meta">
                        <span>⏳ Active Window: {{ $offer->starts_at->format('M d') }} &ndash; {{ $offer->ends_at->format('M d, Y') }}</span>
                    </div>
                    
                    <strong style="display:block;margin:16px 0 8px;font-size:0.8rem;text-transform:uppercase;color:var(--text-secondary);letter-spacing:0.06em">Eligible Hardware:</strong>
                    <div class="offer-products" style="margin-bottom:24px">
                        @foreach($offer->phones as $phone)
                            <a href="{{ route('phones.show', $phone) }}">📱 {{ $phone->name }}</a>
                        @endforeach
                        @foreach($offer->accessories as $accessory)
                            <a href="{{ route('orders.accessory', $accessory) }}">🎧 {{ $accessory->name }}</a>
                        @endforeach
                    </div>

                    @if($offer->phones->isNotEmpty())
                        <a class="btn btn-primary" href="{{ route('phones.index') }}">Browse Eligible Phones &rarr;</a>
                    @elseif($offer->accessories->isNotEmpty())
                        <a class="btn btn-primary" href="{{ route('accessories.index') }}">Browse Eligible Accessories &rarr;</a>
                    @endif
                </article>
            @empty
                <div class="empty-state">There are no active promotional events right now. Check back soon for new offers.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
