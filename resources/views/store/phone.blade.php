@extends('layouts.app')
@section('title', $phone->name.' | TechSphere')

@section('content')
<section class="section">
    <div class="container grid grid-2">
        <div class="card"><img src="{{ $phone->image_url }}" alt="{{ $phone->name }}" style="height:520px;width:100%;object-fit:cover"></div>
        <div>
            <div class="eyebrow">{{ $phone->brand->name }}</div>
            <h1 style="font-size:42px;margin:10px 0">{{ $phone->name }}</h1>
            <div class="price">Rs. {{ number_format($phone->salePrice()) }}</div>
            @if($phone->discountAmount() > 0)<p class="muted">Current offer saves Rs. {{ number_format($phone->discountAmount()) }}</p>@endif
            <p class="muted" style="line-height:1.7">{{ $phone->description }}</p>
            <div class="specs">
                <div class="spec"><strong>RAM</strong><br>{{ $phone->ram }}</div>
                <div class="spec"><strong>Storage</strong><br>{{ $phone->storage }}</div>
                <div class="spec"><strong>Display</strong><br>{{ $phone->display }}</div>
                <div class="spec"><strong>Processor</strong><br>{{ $phone->processor }}</div>
                <div class="spec"><strong>Camera</strong><br>{{ $phone->camera }}</div>
                <div class="spec"><strong>Battery</strong><br>{{ $phone->battery }}</div>
            </div>
            <div class="actions" style="margin-top:20px">
                <a class="btn btn-primary" href="{{ route('orders.phone', $phone) }}">Reserve now</a>
                <a class="btn btn-green" target="_blank" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi TechSphere, I want to order '.$phone->name.' for Rs. '.number_format($phone->salePrice())) }}">WhatsApp</a>
                <a class="btn" href="{{ route('compare', ['phones' => [$phone->id]]) }}">Compare</a>
                @auth
                    <form method="POST" action="{{ route('wishlist.toggle', $phone) }}">@csrf<button class="btn">Wishlist</button></form>
                @else
                    <a class="btn" href="{{ route('login') }}">Login to wishlist</a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
