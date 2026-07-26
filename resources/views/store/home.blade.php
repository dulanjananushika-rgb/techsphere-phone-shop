@extends('layouts.app')
@section('title', $shopSettings['store_name'].' | Genuine Phones and Accessories')
@section('meta_description', 'Browse genuine phones and accessories with live stock, clear prices, local warranty support, and islandwide delivery.')

@section('content')
@php($heroPhone = $featuredPhones->first())
<section class="hero" style="--hero-image: url('{{ $heroPhone?->image_url }}')">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow">Genuine devices. Local support.</div>
            <h1>{{ $shopSettings['store_name'] }}</h1>
            <div class="hero-lead">Clear prices on phones you actually want.</div>
            <p>Check live stock, compare key specifications, reserve online, and choose showroom pickup or islandwide delivery.</p>
            <form class="home-search" method="GET" action="{{ route('phones.index') }}">
                <label class="sr-only" for="home-search">Search phones</label>
                <input id="home-search" name="search" placeholder="Search iPhone, Galaxy, Redmi...">
                <button class="btn btn-primary" type="submit">Search phones</button>
            </form>
            @if($heroPhone)
                <div class="hero-feature">
                    <strong>Featured now</strong>
                    <a href="{{ route('phones.show', $heroPhone) }}">{{ $heroPhone->name }} from Rs. {{ number_format($heroPhone->salePrice()) }}</a>
                </div>
            @endif
            <div class="service-row">
                <span>Live stock</span>
                <span>Warranty support</span>
                <span>Pickup or delivery</span>
            </div>
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">In stock</div>
                <h2>Popular right now</h2>
                <p>Current models ready to reserve from the store.</p>
            </div>
            <a class="btn" href="{{ route('phones.index') }}">View all phones</a>
        </div>
        <div class="grid grid-4">
            @forelse($featuredPhones as $phone)
                @include('store.partials.phone-card', ['phone' => $phone, 'whatsapp' => $whatsapp])
            @empty
                <div class="empty-state">Featured phones will appear here when stock is published.</div>
            @endforelse
        </div>
    </div>
</section>

@if($offers->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Limited time</div>
                <h2>Current offers</h2>
                <p>Discounts automatically applied to eligible products.</p>
            </div>
            <a class="btn" href="{{ route('offers.index') }}">See every deal</a>
        </div>
        <div class="offer-grid">
            @foreach($offers as $offer)
                <article class="offer-card">
                    <div class="offer-badge">Save {{ $offer->discount_percentage }}%</div>
                    <h2>{{ $offer->title }}</h2>
                    <p class="muted">{{ $offer->description }}</p>
                    <div class="offer-meta">Ends {{ $offer->ends_at->format('M d, Y') }}</div>
                    <div class="offer-products">
                        @foreach($offer->phones->take(3) as $phone)
                            <a href="{{ route('phones.show', $phone) }}">{{ $phone->name }}</a>
                        @endforeach
                        @foreach($offer->accessories->take(3) as $accessory)
                            <a href="{{ route('orders.accessory', $accessory) }}">{{ $accessory->name }}</a>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section section-white">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Browse faster</div>
                <h2>Shop by brand</h2>
                <p>Jump straight to the brands you already know.</p>
            </div>
        </div>
        <div class="brand-strip">
            @foreach($brands as $brand)
                <a class="brand-tile" href="{{ route('phones.index', ['brand' => $brand->id]) }}">
                    <strong>{{ $brand->name }}</strong>
                    <p class="muted">{{ $brand->phones_count }} active {{ Str::plural('model', $brand->phones_count) }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
