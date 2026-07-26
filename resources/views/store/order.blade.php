@extends('layouts.app')
@section('title', 'Order Request | TechSphere')

@section('content')
<section class="section">
    <div class="container order-layout">
        <div class="card">
            <div class="product-media">
                <img class="product-img" src="{{ $image }}" alt="{{ $title }}">
            </div>
            <div class="card-body">
                <div class="eyebrow">{{ ucfirst($type) }} order</div>
                <h1>{{ $title }}</h1>
                <p class="muted">{{ $stock > 0 ? $stock.' units available' : 'Currently out of stock' }}</p>
            <div class="price">Rs. {{ number_format($salePrice) }}</div>
            @if($discount > 0)
                <p class="muted">Discount applied: Rs. {{ number_format($discount) }} per item</p>
            @endif
            </div>
        </div>

        <form class="card card-body" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <h2>Reserve this item</h2>
            <p class="muted">Send your details. The shop will confirm stock, payment, and delivery by phone or WhatsApp.</p>
            <input type="hidden" name="item_type" value="{{ $type }}">
            <input type="hidden" name="item_id" value="{{ $item->id }}">

            @if($variants->count())
                <div class="form-group">
                    <label>Variant</label>
                    <select name="product_variant_id" required>
                        <option value="">Select color/storage</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant->id }}" @selected(old('product_variant_id') == $variant->id)>
                                {{ $variant->name }} - Rs. {{ number_format($variant->price) }} - {{ $variant->stock }} left
                            </option>
                        @endforeach
                    </select>
                    @error('product_variant_id')<div class="error">{{ $message }}</div>@enderror
                </div>
            @endif

            <div class="grid grid-2">
                <div class="form-group"><label>Name</label><input name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required></div>
                <div class="form-group"><label>Phone / WhatsApp</label><input name="customer_phone" value="{{ old('customer_phone') }}" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"></div>
                <div class="form-group"><label>Quantity</label><input type="number" min="1" max="10" name="quantity" value="{{ old('quantity', 1) }}" required>@error('quantity')<div class="error">{{ $message }}</div>@enderror</div>
                <div class="form-group"><label>Payment</label><select name="payment_method"><option value="cash">Cash on pickup/delivery</option><option value="bank_transfer">Bank transfer</option><option value="card">Card at store</option></select></div>
                <div class="form-group"><label>Delivery</label><select name="delivery_method"><option value="pickup">Store pickup</option><option value="delivery">Home delivery</option></select></div>
            </div>
            <div class="form-group"><label>Payment reference</label><input name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Bank slip reference or card approval code, if available"></div>
            <div class="form-group"><label>Address</label><textarea name="customer_address">{{ old('customer_address') }}</textarea></div>
            <div class="form-group"><label>Notes</label><textarea name="notes" placeholder="Preferred color, delivery time, or questions.">{{ old('notes') }}</textarea></div>
            <button class="btn btn-primary" @disabled($stock < 1)>Submit order request</button>
        </form>
    </div>
</section>
@endsection
