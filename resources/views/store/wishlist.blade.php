@extends('layouts.app')
@section('title', 'Saved Wishlist | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Personal Favorites</div>
                <h1>My Wishlist</h1>
                <p>Saved flagship smartphones bookmarked for quick review or reservation.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('phones.index') }}">Explore More Phones &rarr;</a>
        </div>
        <div class="grid grid-3">
            @forelse($items as $item)
                @include('store.partials.phone-card', ['phone' => $item->phone, 'whatsapp' => $whatsapp])
            @empty
                <div class="empty-state">
                    <p style="font-size:1.4rem;margin-bottom:8px">❤️ Your wishlist is currently empty</p>
                    <p class="muted">Discover authentic phones from Apple, Samsung, and Xiaomi to build your saved collection.</p>
                    <a class="btn btn-primary" style="margin-top:16px" href="{{ route('phones.index') }}">Browse Catalog Now</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
