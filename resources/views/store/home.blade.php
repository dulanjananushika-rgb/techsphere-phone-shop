@extends('layouts.app')
@section('title', 'TechSphere | Modern Phone Shop')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <div class="eyebrow">Mobile phones and accessories</div>
            <h1>Genuine phones, clear prices, local support.</h1>
            <p>Choose from current iPhone, Samsung, Xiaomi, and Oppo models with warranty-backed accessories and quick WhatsApp ordering.</p>
            <form class="home-search" method="GET" action="{{ route('phones.index') }}">
                <input name="search" placeholder="Search iPhone, Galaxy, Redmi...">
                <button class="btn btn-primary">Search</button>
            </form>
            <div class="service-row">
                <span>Warranty checked</span>
                <span>Card or cash</span>
                <span>Pickup or delivery</span>
            </div>
        </div>
        <div class="hero-panel hero-product">
            <img src="{{ $featuredPhones->first()?->image_url }}" alt="Featured phone" style="height:320px;width:100%;object-fit:cover;border-radius:8px">
            <div class="hero-product-body">
                <div>
                    <div class="eyebrow">Store pick</div>
                    <h2>{{ $featuredPhones->first()?->name }}</h2>
                    <p class="muted">{{ $featuredPhones->first()?->brand->name }} flagship model</p>
                </div>
                <a class="btn btn-small" href="{{ route('phones.index') }}">Browse stock</a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div><h2>Available Now</h2><p>Popular models currently listed in the shop.</p></div>
            <a class="btn" href="{{ route('phones.index') }}">All phones</a>
        </div>
        <div class="grid grid-4">
            @foreach($featuredPhones as $phone)
                @include('store.partials.phone-card', ['phone' => $phone, 'whatsapp' => $whatsapp])
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="container grid grid-2">
        @foreach($offers as $offer)
            <div class="offer-card">
                <div class="offer-badge">{{ $offer->discount_percentage }}% off</div>
                <h2>{{ $offer->title }}</h2>
                <p class="muted">{{ $offer->description }}</p>
                <div class="offer-meta">Valid until {{ $offer->ends_at->format('M d, Y') }}</div>
                <a class="btn btn-small" href="{{ route('offers.index') }}">View deal</a>
            </div>
        @endforeach
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="section-head"><div><h2>Shop by Brand</h2><p>Quickly narrow down the phones you already trust.</p></div></div>
        <div class="brand-strip">
            @foreach($brands as $brand)
                <a class="brand-tile" href="{{ route('phones.index', ['brand' => $brand->id]) }}">
                    <strong>{{ $brand->name }}</strong>
                    <p class="muted">{{ $brand->phones_count }} models available</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
