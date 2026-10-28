@extends('layouts.app')
@section('title', $shopSettings['store_name'].' | Flagship Showroom & Authentic Gear')
@section('meta_description', 'Discover next-generation smartphones and accessories with live atomic stock reservations, transparent pricing, official warranty support, and express delivery.')

@section('content')
@php($heroPhone = $featuredPhones->first())

<!-- Titanium Luxury Showroom Hero -->
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow">Premier Mobile Showroom &bull; Colombo, Sri Lanka</div>
            <h1>The Peak of Mobile Tech.<br><span style="background:var(--grad-titanium);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Direct to You.</span></h1>
            <div class="hero-lead">Zero compromise. Verified showroom inventory. Direct local warranty.</div>
            <p>
                Browse authentic flagships from Apple, Samsung, Xiaomi, and Oppo with atomic 24-hour serial reservations, zero advance payment risk, and showroom pickup or express islandwide delivery.
            </p>

            <!-- Sleek Search Console -->
            <form class="home-search" method="GET" action="{{ route('phones.index') }}">
                <label class="sr-only" for="home-search">Search smartphones and accessories</label>
                <input id="home-search" name="search" placeholder="Search iPhone 16 Pro, Galaxy S25 Ultra, Redmi Note 13...">
                <button class="btn btn-primary" type="submit">
                    <span>⚡ Find Device</span>
                </button>
            </form>

            <!-- Quick Search Tags -->
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:center;margin-bottom:28px">
                <span style="font-size:0.75rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em">Popular Searches:</span>
                <a href="{{ route('phones.index', ['search' => 'iPhone 16']) }}" class="btn btn-small" style="padding:5px 12px;font-size:0.76rem;border-radius:999px;background:rgba(255,255,255,0.03)">iPhone 16 Pro Max</a>
                <a href="{{ route('phones.index', ['search' => 'Galaxy S25']) }}" class="btn btn-small" style="padding:5px 12px;font-size:0.76rem;border-radius:999px;background:rgba(255,255,255,0.03)">Galaxy S25 Ultra</a>
                <a href="{{ route('phones.index', ['search' => 'Redmi Note 13']) }}" class="btn btn-small" style="padding:5px 12px;font-size:0.76rem;border-radius:999px;background:rgba(255,255,255,0.03)">Redmi Note 13</a>
                <a href="{{ route('accessories.index') }}" class="btn btn-small" style="padding:5px 12px;font-size:0.76rem;border-radius:999px;background:rgba(255,255,255,0.03)">Accessories</a>
            </div>

            @if($heroPhone)
                <div class="hero-feature">
                    <strong>🔥 Showroom Spotlight:</strong>
                    <a href="{{ route('phones.show', $heroPhone) }}">
                        {{ $heroPhone->name }} &bull; from Rs. {{ number_format($heroPhone->salePrice()) }}
                    </a>
                </div>
            @endif

            <!-- 4-Pillar Trust Indicators -->
            <div class="service-row">
                <span>⚡ Atomic 24h Stock Lock</span>
                <span>🛡️ 1-Year Local Hardware Warranty</span>
                <span>🚚 Islandwide 24-48h Delivery</span>
                <span>🏬 Colombo Showroom Collection</span>
            </div>
        </div>
    </div>
</section>

<!-- Popular Flagships Grid Section -->
<section class="section section-white">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Verified Showroom Inventory</div>
                <h2>Trending Smartphones</h2>
                <p>Top-rated devices ready for immediate 24-hour reservation.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('phones.index') }}">
                <span>Explore All Phones &rarr;</span>
            </a>
        </div>
        <div class="grid grid-4">
            @forelse($featuredPhones as $phone)
                @include('store.partials.phone-card', ['phone' => $phone, 'whatsapp' => $whatsapp])
            @empty
                <div class="empty-state">Featured devices will be published here as inventory arrives.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Special Deals & Promotional Offers Section -->
@if($offers->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Exclusive Opportunities</div>
                <h2>Limited Promotional Offers</h2>
                <p>Discounts automatically applied at checkout for verified reservations.</p>
            </div>
            <a class="btn" href="{{ route('offers.index') }}">Browse All Deals</a>
        </div>
        <div class="offer-grid">
            @foreach($offers as $offer)
                <article class="offer-card">
                    <div class="offer-badge">⚡ Save {{ $offer->discount_percentage }}% Instant</div>
                    <h2>{{ $offer->title }}</h2>
                    <p class="muted" style="line-height:1.75">{{ $offer->description }}</p>
                    <div class="offer-meta">
                        <span>⏳ Active until {{ $offer->ends_at->format('M d, Y') }}</span>
                    </div>
                    <div class="offer-products">
                        @foreach($offer->phones->take(3) as $phone)
                            <a href="{{ route('phones.show', $phone) }}">📱 {{ $phone->name }}</a>
                        @endforeach
                        @foreach($offer->accessories->take(3) as $accessory)
                            <a href="{{ route('orders.accessory', $accessory) }}">🎧 {{ $accessory->name }}</a>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Brands Showcase Section -->
<section class="section section-white">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Official Brand Partners</div>
                <h2>Shop by Manufacturer</h2>
                <p>Explore specialized device lineups from world-leading tech giants.</p>
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

<!-- TechSphere Advantage Reassurance Section -->
<section class="section" style="border-top:1px solid var(--border-subtle);background:linear-gradient(180deg, rgba(18,24,41,0.5) 0%, transparent 100%)">
    <div class="container">
        <div style="text-align:center;max-width:700px;margin:0 auto 48px">
            <div class="eyebrow">Why Choose TechSphere</div>
            <h2>Built For The Discerning Buyer</h2>
            <p class="muted">We remove the frustration of out-of-stock bait, fake claims, and unverified warranties.</p>
        </div>

        <div class="grid grid-3">
            <div class="card card-body" style="padding:34px;border-radius:var(--radius-lg)">
                <div style="font-size:2.2rem;margin-bottom:14px">🔒</div>
                <h3>Atomic Stock Guarantee</h3>
                <p class="muted" style="line-height:1.75">When you click reserve, that exact serial unit is locked in database memory for 24 hours. No double-selling, no disappointments.</p>
            </div>
            <div class="card card-body" style="padding:34px;border-radius:var(--radius-lg)">
                <div style="font-size:2.2rem;margin-bottom:14px">🛡️</div>
                <h3>Authentic Hardware & Warranty</h3>
                <p class="muted" style="line-height:1.75">100% factory original international and local variants with comprehensive warranty backing and repair assistance.</p>
            </div>
            <div class="card card-body" style="padding:34px;border-radius:var(--radius-lg)">
                <div style="font-size:2.2rem;margin-bottom:14px">💳</div>
                <h3>Zero Advance Charge Risk</h3>
                <p class="muted" style="line-height:1.75">Pay securely only when inspecting your phone in our Colombo showroom or upon verified doorstep delivery.</p>
            </div>
        </div>
    </div>
</section>
@endsection
