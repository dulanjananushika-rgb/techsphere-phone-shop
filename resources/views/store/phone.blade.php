@extends('layouts.app')
@section('title', $phone->name.' | '.$shopSettings['store_name'])
@section('meta_description', Str::limit($phone->description, 150))

@section('content')
<section class="section section-white">
    <div class="container grid grid-2" style="align-items:start;gap:48px">
        <!-- Media Showcase Stage -->
        <div class="card" style="position:sticky;top:90px">
            <div class="product-media" style="padding:48px 32px;min-height:480px;background:radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 80%)">
                @if($phone->is_featured)
                    <span class="stock-badge">🔥 Verified Flagship</span>
                @endif
                @if($phone->discountAmount() > 0)
                    <span class="discount-badge">⚡ Save {{ $phone->activeOffer()?->discount_percentage }}% Instant</span>
                @endif
                <img class="product-img" src="{{ $phone->image_url }}" alt="{{ $phone->name }}" style="max-height:420px;filter:drop-shadow(0 25px 35px rgba(0,0,0,0.7))">
            </div>
        </div>

        <!-- Specifications & Purchase Details -->
        <div>
            <div class="eyebrow">{{ $phone->brand->name }} &bull; Official Sri Lanka Stock</div>
            <h1 style="font-size:clamp(2rem, 3.5vw, 2.8rem);margin:6px 0 16px">{{ $phone->name }}</h1>

            <div class="product-price-row" style="margin-bottom:12px">
                <div class="price" style="font-size:2.2rem;color:var(--text-white)">Rs. {{ number_format($phone->salePrice()) }}</div>
                @if($phone->discountAmount() > 0 || $phone->old_price)
                    <div class="old-price" style="font-size:1.15rem">Rs. {{ number_format($phone->old_price ?: $phone->price) }}</div>
                @endif
            </div>

            @if($phone->discountAmount() > 0)
                <div class="notice" style="padding:10px 16px;margin-bottom:20px;font-size:0.86rem">
                    <span>You save <strong>Rs. {{ number_format($phone->discountAmount()) }}</strong> with active promotional discount!</span>
                </div>
            @endif

            <p class="muted" style="line-height:1.8;font-size:1.02rem;margin-bottom:24px">{{ $phone->description }}</p>

            <div class="product-stock" style="font-size:0.95rem;margin-bottom:24px">
                {{ $phone->availableStock() > 0 ? $phone->availableStock().' units verified in showroom stock' : 'Currently out of showroom stock' }}
            </div>

            <!-- Available Variant Tiers -->
            @if($phone->variants->where('is_active', true)->isNotEmpty())
                <div class="conditional-panel" style="margin-bottom:28px">
                    <strong style="display:block;margin-bottom:12px;color:var(--text-white);font-size:0.92rem;text-transform:uppercase;letter-spacing:0.06em">Available Storage & Color Options:</strong>
                    <div class="offer-products">
                        @foreach($phone->variants->where('is_active', true) as $variant)
                            <span>
                                <strong>{{ $variant->name }}</strong> &bull; Rs. {{ number_format($variant->price) }} &bull; <small style="color:var(--emerald-mint)">{{ $variant->stock }} left</small>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Hardware Specifications Grid -->
            <div style="margin-bottom:32px">
                <h3 style="font-size:1.1rem;margin-bottom:14px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary)">Technical Specifications</h3>
                <div class="specs" style="grid-template-columns:repeat(2, 1fr);gap:12px">
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem">⚡ <strong>RAM:</strong>&nbsp;{{ $phone->ram }}</div>
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem">💾 <strong>Storage:</strong>&nbsp;{{ $phone->storage }}</div>
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem">📱 <strong>Display:</strong>&nbsp;{{ $phone->display ?: 'Standard Retina' }}</div>
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem">⚙️ <strong>Chipset:</strong>&nbsp;{{ $phone->processor ?: 'High-Performance' }}</div>
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem">📷 <strong>Optics:</strong>&nbsp;{{ $phone->camera ?: 'Pro Camera System' }}</div>
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem">🔋 <strong>Battery:</strong>&nbsp;{{ $phone->battery ?: 'All-Day Battery' }}</div>
                    <div class="spec" style="padding:10px 14px;font-size:0.88rem;grid-column:1/-1">🖥️ <strong>Platform:</strong>&nbsp;{{ $phone->os ?: 'Latest OS' }}</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display:flex;flex-direction:column;gap:12px">
                <a @class(['btn', 'btn-primary', 'btn-block', 'disabled' => $phone->availableStock() < 1])
                   style="padding:16px;font-size:1.05rem"
                   href="{{ $phone->availableStock() > 0 ? route('orders.phone', $phone) : '#' }}"
                   @if($phone->availableStock() < 1) aria-disabled="true" @endif>
                    <span>{{ $phone->availableStock() > 0 ? '🚀 Reserve Device Now (Atomic Stock Lock)' : 'Out of Stock' }}</span>
                </a>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <a class="btn btn-green" target="_blank" rel="noopener" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I am interested in reserving '.$phone->name.'. Could you confirm color and delivery details?') }}">
                        <span>💬 WhatsApp Inquiry</span>
                    </a>
                    <a class="btn" href="{{ route('compare', ['phones' => [$phone->id]]) }}">
                        <span>⚖️ Add to Compare</span>
                    </a>
                </div>

                @auth
                    <form method="POST" action="{{ route('wishlist.toggle', $phone) }}">
                        @csrf
                        <button class="btn btn-block" type="submit">❤️ Add to Wishlist</button>
                    </form>
                @else
                    <a class="btn btn-block" href="{{ route('login') }}">❤️ Log In to Save to Wishlist</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Related Devices from Brand -->
@if($related->isNotEmpty())
<section class="section" style="border-top:1px solid var(--border-subtle)">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Brand Portfolio</div>
                <h2>More from {{ $phone->brand->name }}</h2>
                <p>Other popular models currently available in store.</p>
            </div>
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
