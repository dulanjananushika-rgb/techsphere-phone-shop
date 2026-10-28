@extends('layouts.app')
@section('title', 'Genuine Tech Accessories | '.$shopSettings['store_name'])
@section('meta_description', 'High-speed chargers, MagSafe cases, and true wireless audio accessories with verified stock and instant reservations.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Verified Tech Accessories</div>
                <h1>Essential Mobile Gear</h1>
                <p>Authentic charging adapters, protection, and premium audio with verified stock.</p>
            </div>
        </div>

        <!-- Category Pill Tabs -->
        <div class="category-tabs">
            <a @class(['btn', 'btn-small', 'active' => !request('category')]) href="{{ route('accessories.index') }}">
                ⚡ All Gear
            </a>
            @foreach($categories as $category)
                <a @class(['btn', 'btn-small', 'active' => request('category') === $category]) href="{{ route('accessories.index', ['category' => $category]) }}">
                    {{ $category }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-3">
            @forelse($accessories as $item)
                <article class="card product-card">
                    <div class="product-media" style="min-height:220px">
                        @if($item->discountAmount() > 0)
                            <span class="discount-badge">⚡ Save {{ $item->activeOffer()?->discount_percentage }}%</span>
                        @endif
                        <img class="product-img" src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy">
                    </div>
                    <div class="card-body">
                        <div class="product-brand">{{ $item->category }}</div>
                        <h3>{{ $item->name }}</h3>
                        <div class="product-stock">{{ $item->availableStock() }} units available</div>
                        <div class="product-price-row">
                            <div class="price">Rs. {{ number_format($item->salePrice()) }}</div>
                            @if($item->discountAmount() > 0)
                                <div class="old-price">Rs. {{ number_format($item->price) }}</div>
                            @endif
                        </div>
                        <p class="muted" style="margin-bottom:20px;line-height:1.6">{{ $item->description }}</p>
                        <div class="product-actions" style="grid-template-columns:1fr">
                            <a @class(['btn', 'btn-primary', 'disabled' => $item->availableStock() < 1])
                               href="{{ $item->availableStock() > 0 ? route('orders.accessory', $item) : '#' }}"
                               @if($item->availableStock() < 1) aria-disabled="true" @endif>
                                {{ $item->availableStock() > 0 ? '🚀 Reserve Gear Now' : 'Out of stock' }}
                            </a>
                            <a class="btn btn-green btn-small" target="_blank" rel="noopener" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I would like to reserve the '.$item->name.'.') }}">
                                <span>💬 WhatsApp Inquiry</span>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">No accessories are currently published in this category.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
