<article class="card product-card">
    <a class="product-media" href="{{ route('phones.show', $phone) }}">
        @if($phone->is_featured)
            <span class="stock-badge">Popular</span>
        @endif
        @if($phone->discountAmount() > 0)
            <span class="discount-badge">Save {{ $phone->activeOffer()?->discount_percentage }}%</span>
        @endif
        <img class="product-img" src="{{ $phone->image_url }}" alt="{{ $phone->name }}" loading="lazy">
    </a>
    <div class="card-body">
        <div class="product-brand">{{ $phone->brand->name }}</div>
        <h3><a href="{{ route('phones.show', $phone) }}">{{ $phone->name }}</a></h3>
        <div class="product-stock">{{ $phone->availableStock() > 0 ? $phone->availableStock().' units available' : 'Currently out of stock' }}</div>
        <div class="product-price-row">
            <div class="price">Rs. {{ number_format($phone->salePrice()) }}</div>
            @if($phone->discountAmount() > 0 || $phone->old_price)
                <div class="old-price">Rs. {{ number_format($phone->old_price ?: $phone->price) }}</div>
            @endif
        </div>
        <div class="specs">
            <div class="spec">{{ $phone->ram }} RAM</div>
            <div class="spec">{{ $phone->storage }}</div>
            <div class="spec">{{ $phone->camera ?: 'Camera details available' }}</div>
            <div class="spec">{{ $phone->battery ?: 'Battery details available' }}</div>
        </div>
        <div class="product-actions">
            <a class="btn btn-small" href="{{ route('phones.show', $phone) }}">View details</a>
            <a class="btn btn-small" href="{{ route('compare', ['phones' => [$phone->id]]) }}">Compare</a>
            <a @class(['btn', 'btn-primary', 'btn-small', 'disabled' => $phone->availableStock() < 1])
               href="{{ $phone->availableStock() > 0 ? route('orders.phone', $phone) : '#' }}"
               @if($phone->availableStock() < 1) aria-disabled="true" @endif>
                {{ $phone->availableStock() > 0 ? 'Reserve now' : 'Out of stock' }}
            </a>
            <a class="btn btn-green btn-small" target="_blank" rel="noopener" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I am interested in '.$phone->name.'.') }}">Ask on WhatsApp</a>
        </div>
    </div>
</article>
