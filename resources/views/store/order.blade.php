@extends('layouts.app')
@section('title', 'Reserve '.$title.' | '.$shopSettings['store_name'])

@section('content')
@php
    $offerPercentage = $item->activeOffer()?->discount_percentage ?? 0;
    $selectedDelivery = old('delivery_method', 'pickup');
    $selectedPayment = old('payment_method', 'cash');
@endphp
<section class="section">
    <div class="container order-layout">
        <!-- Left Column: Item Stage & Stock Guarantee -->
        <aside class="card order-product" style="position:sticky;top:90px">
            <div class="product-media" style="padding:36px 24px;min-height:300px;background:radial-gradient(circle at 50% 50%, rgba(0,242,254,0.1) 0%, transparent 80%)">
                @if($offerPercentage > 0)
                    <span class="discount-badge">⚡ Save {{ $offerPercentage }}% Instant</span>
                @endif
                <img class="product-img" src="{{ $image }}" alt="{{ $title }}" style="max-height:240px;filter:drop-shadow(0 15px 25px rgba(0,0,0,0.7))">
            </div>
            <div class="card-body">
                <div class="eyebrow">{{ ucfirst($type) }} &bull; Atomic Stock Reservation</div>
                <h1 style="font-size:1.6rem;margin:6px 0 10px">{{ $title }}</h1>
                <div class="price" data-product-price style="font-size:1.8rem;color:var(--text-white)">Rs. {{ number_format($salePrice) }}</div>
                @if($discount > 0)
                    <div class="old-price" style="font-size:0.95rem">Regular price Rs. {{ number_format($item->price) }}</div>
                @endif
                <p class="product-stock" data-product-stock style="margin:12px 0">{{ $stock > 0 ? $stock.' units available' : 'Currently out of stock' }}</p>

                <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:14px;margin-top:16px;font-size:0.84rem;color:var(--text-secondary);line-height:1.6">
                    <strong style="color:var(--titanium-gold);display:block;margin-bottom:4px">🔒 24-Hour Stock Guarantee:</strong>
                    Your hardware serial is reserved for {{ $shopSettings['reservation_hours'] }} hours without payment while our staff contacts you.
                </div>
            </div>
        </aside>

        <!-- Right Column: Interactive Order Command Form -->
        <form class="card checkout-card"
              method="POST"
              action="{{ route('orders.store') }}"
              data-checkout
              data-lock-submit
              data-base-price="{{ $salePrice }}"
              data-base-stock="{{ $stock }}"
              data-delivery-fee="{{ $shopSettings['delivery_fee'] }}">
            @csrf
            <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
            <input type="hidden" name="item_type" value="{{ $type }}">
            <input type="hidden" name="item_id" value="{{ $item->id }}">

            <div class="eyebrow">Zero Advance Risk Request</div>
            <h2 style="margin:4px 0 8px">Customer & Delivery Configuration</h2>
            <p class="muted" style="margin-bottom:24px;line-height:1.6">No online credit card charge is taken here. You inspect and verify your device before completing payment.</p>

            @if($variants->count())
                <div class="form-group">
                    <label for="product-variant">Choose Color / Internal Storage Variant</label>
                    <select id="product-variant" name="product_variant_id" data-variant required>
                        <option value="">Select an available configuration tier</option>
                        @foreach($variants as $variant)
                            @php($variantSalePrice = (int) round($variant->price * (100 - $offerPercentage) / 100))
                            <option value="{{ $variant->id }}"
                                    data-price="{{ $variantSalePrice }}"
                                    data-stock="{{ $variant->stock }}"
                                    @selected(old('product_variant_id') == $variant->id)
                                    @disabled($variant->stock < 1)>
                                {{ $variant->name }} - Rs. {{ number_format($variantSalePrice) }} ({{ $variant->stock }} left)
                            </option>
                        @endforeach
                    </select>
                    @error('product_variant_id')<div class="error">{{ $message }}</div>@enderror
                </div>
            @endif

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="customer-name">Recipient Full Name</label>
                    <input id="customer-name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" autocomplete="name" required placeholder="Dulanjan Anushika">
                    @error('customer_name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="customer-phone">Mobile / WhatsApp Hotline</label>
                    <input id="customer-phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="0771234567" inputmode="tel" autocomplete="tel" required>
                    @error('customer_phone')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="customer-email">Email Address (Invoicing & Tracking)</label>
                    <input id="customer-email" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" autocomplete="email" placeholder="you@domain.com">
                    <p class="field-help">Digital invoice and status timeline will be sent to this email.</p>
                </div>
                <div class="form-group">
                    <label for="order-quantity">Quantity (Max 10 per order)</label>
                    <input id="order-quantity" type="number" min="1" max="{{ min(10, max(1, $stock)) }}" name="quantity" value="{{ old('quantity', 1) }}" data-quantity required>
                    @error('quantity')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment Preference</label>
                    <select id="payment-method" name="payment_method" data-payment required>
                        <option value="cash" @selected($selectedPayment === 'cash')>💵 Cash on Delivery / Showroom Collection</option>
                        <option value="bank_transfer" @selected($selectedPayment === 'bank_transfer')>🏦 Bank Transfer / Direct Deposit</option>
                        <option value="card" @selected($selectedPayment === 'card')>💳 Card Payment at Colombo Showroom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="delivery-method">Fulfilment Channel</label>
                    <select id="delivery-method" name="delivery_method" data-delivery required>
                        <option value="pickup" @selected($selectedDelivery === 'pickup')>🏬 Colombo Showroom Pickup (Free)</option>
                        <option value="delivery" @selected($selectedDelivery === 'delivery')>🚚 Islandwide Doorstep Express - Rs. {{ number_format((int) $shopSettings['delivery_fee']) }}</option>
                    </select>
                </div>
            </div>

            <!-- Bank Transfer Details Panel -->
            <div class="conditional-panel" data-bank-panel @if($selectedPayment !== 'bank_transfer') hidden @endif>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                    <span style="font-size:1.2rem">🏦</span>
                    <strong style="color:var(--text-white)">Bank Transfer Instructions:</strong>
                </div>
                <p class="muted" style="font-size:0.9rem;line-height:1.6;margin-bottom:14px">
                    <strong>{{ $shopSettings['bank_name'] }}</strong> &bull; Account: <span style="color:#ffffff;font-family:monospace;font-weight:700">{{ $shopSettings['bank_account_number'] }}</span><br>
                    Beneficiary Name: <strong>{{ $shopSettings['bank_account_name'] }}</strong>
                </p>
                <div class="form-group" style="margin-bottom:0">
                    <label for="payment-reference">Transaction ID / Slip Reference</label>
                    <input id="payment-reference" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Enter reference number or online slip reference">
                    @error('payment_reference')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Delivery Address Panel -->
            <div class="form-group" data-address-group @if($selectedDelivery !== 'delivery') hidden @endif>
                <label for="customer-address">Islandwide Delivery Address</label>
                <textarea id="customer-address" name="customer_address" autocomplete="street-address" placeholder="Address, City, Postal Code" rows="3">{{ old('customer_address') }}</textarea>
                @error('customer_address')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="order-notes">Special Instructions or Preferred Contact Time (Optional)</label>
                <textarea id="order-notes" name="notes" placeholder="e.g. Call after 2:00 PM, deliver to office reception..." rows="2">{{ old('notes') }}</textarea>
            </div>

            <!-- Dynamic Live Calculation Total Panel -->
            <div class="checkout-total" style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:20px;margin:24px 0" aria-live="polite">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:var(--text-secondary);font-size:0.92rem">
                    <span>Hardware Subtotal:</span>
                    <strong data-subtotal style="color:#ffffff">Rs. {{ number_format($salePrice) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:12px;color:var(--text-secondary);font-size:0.92rem">
                    <span>Fulfilment & Delivery:</span>
                    <strong data-delivery-fee style="color:#ffffff">Rs. {{ $selectedDelivery === 'delivery' ? number_format((int) $shopSettings['delivery_fee']) : '0' }}</strong>
                </div>
                <div class="total-row" style="display:flex;justify-content:space-between;padding-top:12px;border-top:1px solid var(--border-subtle);font-size:1.25rem">
                    <span style="font-weight:700">Estimated Total:</span>
                    <strong data-total style="color:var(--sapphire-light);font-weight:900">Rs. {{ number_format($salePrice + ($selectedDelivery === 'delivery' ? (int) $shopSettings['delivery_fee'] : 0)) }}</strong>
                </div>
            </div>

            <button class="btn btn-primary btn-block" style="padding:16px;font-size:1.08rem" type="submit" data-loading-text="Securing Stock Reservation..." @disabled($stock < 1)>
                <span>🚀 Confirm Reservation (Lock Stock for 24h)</span>
            </button>
        </form>
    </div>
</section>
@endsection
