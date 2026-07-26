@extends('layouts.app')
@section('title', 'Accessories | TechSphere')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head"><div><h2>Accessories</h2><p>Everyday essentials for protection, charging, and audio.</p></div></div>
        <div class="category-tabs">
            <a class="btn btn-small" href="{{ route('accessories.index') }}">All</a>
            @foreach($categories as $category)
                <a class="btn btn-small" href="{{ route('accessories.index', ['category' => $category]) }}">{{ $category }}</a>
            @endforeach
        </div>
        <div class="grid grid-3">
            @foreach($accessories as $item)
                <div class="card">
                    <img class="product-img" src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    <div class="card-body">
                        <div class="eyebrow">{{ $item->category }}</div>
                        <h3>{{ $item->name }}</h3>
                        <div class="price">Rs. {{ number_format($item->salePrice()) }}</div>
                        @if($item->discountAmount() > 0)<div class="old-price">Rs. {{ number_format($item->price) }}</div>@endif
                        <p class="muted">{{ $item->description }}</p>
                        <div class="actions">
                            <a class="btn btn-primary" href="{{ route('orders.accessory', $item) }}">Reserve</a>
                            <a class="btn btn-green" target="_blank" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi TechSphere, I want to order '.$item->name.' for Rs. '.number_format($item->salePrice())) }}">WhatsApp</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
