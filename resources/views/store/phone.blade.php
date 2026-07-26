@extends('layouts.app')
@section('title', $phone->name.' | '.$shopSettings['store_name'])
@section('meta_description', Str::limit($phone->description, 150))

@section('content')
<section class="section section-white">
    <div class="container grid grid-2">
        <div class="card">
            <div class="product-media">
                @if($phone->discountAmount() > 0)
                    <span class="discount-badge">Save {{ $phone->activeOffer()?->discount_percentage }}%</span>
                @endif
                <img class="product-img" src="{{ $phone->image_url }}" alt="{{ $phone->name }}" style="height:500px">
            </div>
        </div>
        <div>
            <div class="eyebrow">{{ $phone->brand->name }}</div>
            <h1 style="font-size:42px;margin:9px 0 12px">{{ $phone->name }}</h1>
            <div class="price">Rs. {{ number_format($phone->salePrice()) }}</div>
            @if($phone->discountAmount() > 0)
                <div class="old-price">Regular price Rs. {{ number_format($phone->price) }}</div>
                <p class="offer-meta">You save Rs. {{ number_format($phone->discountAmount()) }}</p>
            @endif
            <p class="muted" style="line-height:1.75">{{ $phone->description }}</p>
            <p><strong>{{ $phone->availableStock() }}</strong> total units available</p>

            @if($phone->variants->where('is_active', true)->isNotEmpty())
                <div class="conditional-panel">
                    <strong>Available options</strong>
                    <div class="offer-products">
                        @foreach($phone->variants->where('is_active', true) as $variant)
                            <span>{{ $variant->name }} | Rs. {{ number_format($variant->price) }} | {{ $variant->stock }} left</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="specs">
                <div class="spec"><strong>RAM:&nbsp;</strong>{{ $phone->ram }}</div>
                <div class="spec"><strong>Storage:&nbsp;</strong>{{ $phone->storage }}</div>
                <div class="spec"><strong>Display:&nbsp;</strong>{{ $phone->display ?: 'Ask store' }}</div>
                <div class="spec"><strong>Processor:&nbsp;</strong>{{ $phone->processor ?: 'Ask store' }}</div>
                <div class="spec"><strong>Camera:&nbsp;</strong>{{ $phone->camera ?: 'Ask store' }}</div>
                <div class="spec"><strong>Battery:&nbsp;</strong>{{ $phone->battery ?: 'Ask store' }}</div>
                <div class="spec"><strong>OS:&nbsp;</strong>{{ $phone->os ?: 'Ask store' }}</div>
            </div>

            <div class="actions" style="margin-top:20px">
                <a @class(['btn', 'btn-primary', 'disabled' => $phone->availableStock() < 1])
                   href="{{ $phone->availableStock() > 0 ? route('orders.phone', $phone) : '#' }}"
                   @if($phone->availableStock() < 1) aria-disabled="true" @endif>
                    {{ $phone->availableStock() > 0 ? 'Reserve this phone' : 'Out of stock' }}
                </a>
                <a class="btn btn-green" target="_blank" rel="noopener" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I am interested in '.$phone->name.'.') }}">Ask on WhatsApp</a>
                <a class="btn" href="{{ route('compare', ['phones' => [$phone->id]]) }}">Add to compare</a>
                @auth
                    <form method="POST" action="{{ route('wishlist.toggle', $phone) }}">
                        @csrf
                        <button class="btn" type="submit">Save to wishlist</button>
                    </form>
                @else
                    <a class="btn" href="{{ route('login') }}">Log in to save</a>
                @endauth
            </div>
        </div>
    </div>
</section>

@if($related->isNotEmpty())
<section class="section">
    <div class="container">
        <div class="section-head">
            <div><h2>More from {{ $phone->brand->name }}</h2><p>Other active models from the same brand.</p></div>
        </div>
        <div class="grid grid-3">
            @foreach($related as $relatedPhone)
                @include('store.partials.phone-card', ['phone' => $relatedPhone, 'whatsapp' => $whatsapp])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
