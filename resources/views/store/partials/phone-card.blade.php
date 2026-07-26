<div class="card product-card">
    <div class="product-media">
        @if($phone->is_featured)<span class="stock-badge">Popular</span>@endif
        <img class="product-img" src="{{ $phone->image_url }}" alt="{{ $phone->name }}">
    </div>
    <div class="card-body">
        <div class="product-brand">{{ $phone->brand->name }}</div>
        <div class="title-row">
            <div>
                <h3 style="margin:0">{{ $phone->name }}</h3>
                <div class="muted">{{ $phone->availableStock() > 0 ? $phone->availableStock().' units in stock' : 'Check availability' }}</div>
            </div>
            <div style="text-align:right">
                <div class="price">Rs. {{ number_format($phone->salePrice()) }}</div>
                @if($phone->discountAmount() > 0 || $phone->old_price)<div class="old-price">Rs. {{ number_format($phone->old_price ?: $phone->price) }}</div>@endif
            </div>
        </div>
        <div class="specs">
            <div class="spec">{{ $phone->ram }} RAM</div>
            <div class="spec">{{ $phone->storage }}</div>
            <div class="spec">{{ $phone->camera }}</div>
            <div class="spec">{{ $phone->battery }}</div>
        </div>
        <div class="product-actions">
            <a class="btn btn-small" href="{{ route('phones.show', $phone) }}">Details</a>
            <a class="btn btn-small" href="{{ route('compare', ['phones' => [$phone->id]]) }}">Compare</a>
            <a class="btn btn-primary btn-small" href="{{ route('orders.phone', $phone) }}">Reserve</a>
            <a class="btn btn-green btn-small" target="_blank" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi TechSphere, I want to order '.$phone->name.' for Rs. '.number_format($phone->salePrice())) }}">WhatsApp</a>
        </div>
    </div>
</div>
