@extends('layouts.app')
@section('title', 'Wishlist | TechSphere')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head"><div><h2>My Wishlist</h2><p>Saved phones for later review.</p></div></div>
        <div class="grid grid-3">
            @forelse($items as $item)
                @include('store.partials.phone-card', ['phone' => $item->phone, 'whatsapp' => $whatsapp])
            @empty
                <div class="card card-body">Your wishlist is empty. Browse phones and save a favorite.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
