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
        <aside class="card order-product">
            <div class="product-media">
                @if($offerPercentage > 0)
                    <span class="discount-badge">Save {{ $offerPercentage }}%</span>
                @endif
                <img class="product-img" src="{{ $image }}" alt="{{ $title }}">
            </div>
            <div class="card-body">
                <div class="eyebrow">{{ ucfirst($type) }} reservation</div>
                <h1 style="font-size:25px;margin:8px 0">{{ $title }}</h1>
                <div class="price" data-product-price>Rs. {{ number_format($salePrice) }}</div>
                @if($discount > 0)
                    <div class="old-price">Regular price Rs. {{ number_format($item->price) }}</div>
                @endif
                <p class="product-stock" data-product-stock>{{ $stock > 0 ? $stock.' units available' : 'Currently out of stock' }}</p>
                <p class="muted">Stock is reserved for {{ $shopSettings['reservation_hours'] }} hours while the shop confirms your order.</p>
            </div>
        </aside>

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

            <div class="eyebrow">Secure reservation request</div>
            <h2>Customer and order details</h2>
            <p class="muted">No online card charge is taken here. We confirm stock, payment, and delivery before completing the sale.</p>

            @if($variants->count())
                <div class="form-group">
                    <label for="product-variant">Color / storage option</label>
                    <select id="product-variant" name="product_variant_id" data-variant required>
                        <option value="">Select an available option</option>
                        @foreach($variants as $variant)
                            @php($variantSalePrice = (int) round($variant->price * (100 - $offerPercentage) / 100))
                            <option value="{{ $variant->id }}"
                                    data-price="{{ $variantSalePrice }}"
                                    data-stock="{{ $variant->stock }}"
                                    @selected(old('product_variant_id') == $variant->id)
                                    @disabled($variant->stock < 1)>
                                {{ $variant->name }} - Rs. {{ number_format($variantSalePrice) }} - {{ $variant->stock }} left
                            </option>
                        @endforeach
                    </select>
                    @error('product_variant_id')<div class="error">{{ $message }}</div>@enderror
                </div>
            @endif

            <div class="grid grid-2">
                <div class="form-group">
                    <label for="customer-name">Full name</label>
                    <input id="customer-name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" autocomplete="name" required>
                    @error('customer_name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="customer-phone">Mobile / WhatsApp</label>
                    <input id="customer-phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="0771234567" inputmode="tel" autocomplete="tel" required>
                    @error('customer_phone')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="customer-email">Email for updates</label>
                    <input id="customer-email" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" autocomplete="email">
                    <p class="field-help">Order confirmation and status updates are emailed here.</p>
                </div>
                <div class="form-group">
                    <label for="order-quantity">Quantity</label>
                    <input id="order-quantity" type="number" min="1" max="{{ min(10, max(1, $stock)) }}" name="quantity" value="{{ old('quantity', 1) }}" data-quantity required>
                    @error('quantity')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="payment-method">Payment method</label>
                    <select id="payment-method" name="payment_method" data-payment required>
                        <option value="cash" @selected($selectedPayment === 'cash')>Cash on pickup / delivery</option>
                        <option value="bank_transfer" @selected($selectedPayment === 'bank_transfer')>Bank transfer</option>
                        <option value="card" @selected($selectedPayment === 'card')>Card at showroom</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="delivery-method">Fulfilment</label>
                    <select id="delivery-method" name="delivery_method" data-delivery required>
                        <option value="pickup" @selected($selectedDelivery === 'pickup')>Showroom pickup - free</option>
                        <option value="delivery" @selected($selectedDelivery === 'delivery')>Islandwide delivery - Rs. {{ number_format((int) $shopSettings['delivery_fee']) }}</option>
                    </select>
                </div>
            </div>

            <div class="conditional-panel" data-bank-panel @if($selectedPayment !== 'bank_transfer') hidden @endif>
                <strong>Bank transfer details</strong>
                <p class="muted">{{ $shopSettings['bank_name'] }}<br>{{ $shopSettings['bank_account_name'] }}<br>Account: {{ $shopSettings['bank_account_number'] }}</p>
                <div class="form-group" style="margin-bottom:0">
                    <label for="payment-reference">Transfer reference</label>
                    <input id="payment-reference" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Transaction or slip reference">
                    @error('payment_reference')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group" data-address-group @if($selectedDelivery !== 'delivery') hidden @endif>
                <label for="customer-address">Delivery address</label>
                <textarea id="customer-address" name="customer_address" autocomplete="street-address" placeholder="House number, street, city, and postal code">{{ old('customer_address') }}</textarea>
                @error('customer_address')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="order-notes">Order notes</label>
                <textarea id="order-notes" name="notes" placeholder="Preferred contact time or anything the shop should know">{{ old('notes') }}</textarea>
            </div>

            <div class="checkout-total" aria-live="polite">
                <div><span>Items</span><strong data-subtotal>Rs. {{ number_format($salePrice) }}</strong></div>
                <div><span>Delivery</span><strong data-delivery-fee>Rs. {{ $selectedDelivery === 'delivery' ? number_format((int) $shopSettings['delivery_fee']) : '0' }}</strong></div>
                <div class="total-row"><span>Estimated total</span><strong data-total>Rs. {{ number_format($salePrice + ($selectedDelivery === 'delivery' ? (int) $shopSettings['delivery_fee'] : 0)) }}</strong></div>
            </div>

            <button class="btn btn-primary btn-block" type="submit" data-loading-text="Submitting securely..." @disabled($stock < 1)>Submit reservation</button>
        </form>
    </div>
</section>
@endsection
