@extends('layouts.app')
@section('title', 'Accessories | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Accessories</div>
                <h1>Everyday essentials</h1>
                <p>Charging, protection, and audio accessories with live stock.</p>
            </div>
        </div>
        <div class="category-tabs">
            <a @class(['btn', 'btn-small', 'active' => !request('category')]) href="{{ route('accessories.index') }}">All</a>
            @foreach($categories as $category)
                <a @class(['btn', 'btn-small', 'active' => request('category') === $category]) href="{{ route('accessories.index', ['category' => $category]) }}">{{ $category }}</a>
            @endforeach
        </div>

        <div class="grid grid-3">
            @forelse($accessories as $item)
                <article class="card product-card">
                    <div class="product-media">
                        @if($item->discountAmount() > 0)
                            <span class="discount-badge">Save {{ $item->activeOffer()?->discount_percentage }}%</span>
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
                        <p class="muted">{{ $item->description }}</p>
                        <div class="product-actions">
                            <a @class(['btn', 'btn-primary', 'disabled' => $item->availableStock() < 1])
                               href="{{ $item->availableStock() > 0 ? route('orders.accessory', $item) : '#' }}"
                               @if($item->availableStock() < 1) aria-disabled="true" @endif>
                                {{ $item->availableStock() > 0 ? 'Reserve now' : 'Out of stock' }}
                            </a>
                            <a class="btn btn-green" target="_blank" rel="noopener" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I am interested in '.$item->name.'.') }}">Ask on WhatsApp</a>
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
