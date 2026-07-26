@extends('layouts.admin')
@section('title', $variant->exists ? 'Edit Variant' : 'Add Variant')

@section('content')
<div class="toolbar">
    <div><h1>{{ $variant->exists ? 'Edit variant' : 'Add variant' }}</h1><p>Track the real sellable unit by SKU, option, price, and stock.</p></div>
    <a class="btn" href="{{ route('admin.variants.index') }}">Back to variants</a>
</div>

@php
    $currentTarget = old('product_target');
    if (! $currentTarget && $variant->exists) {
        $currentTarget = ($variant->product_type === \App\Models\Phone::class ? 'phone' : 'accessory').':'.$variant->product_id;
    }
@endphp

<form class="card card-body" method="POST" data-lock-submit action="{{ $variant->exists ? route('admin.variants.update', $variant) : route('admin.variants.store') }}">
    @csrf
    @if($variant->exists) @method('PUT') @endif
    <div class="grid grid-2">
        <div class="form-group">
            <label for="variant-product">Product</label>
            <select id="variant-product" name="product_target" required>
                <option value="">Select product</option>
                <optgroup label="Phones">
                    @foreach($phones as $phone)
                        <option value="phone:{{ $phone->id }}" @selected($currentTarget === 'phone:'.$phone->id)>{{ $phone->name }} - {{ $phone->brand->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Accessories">
                    @foreach($accessories as $accessory)
                        <option value="accessory:{{ $accessory->id }}" @selected($currentTarget === 'accessory:'.$accessory->id)>{{ $accessory->name }} - {{ $accessory->category }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        <div class="form-group">
            <label for="variant-sku">SKU</label>
            <input id="variant-sku" name="sku" value="{{ old('sku', $variant->sku) }}" placeholder="IPH16PM-256-BLK" required>
        </div>
        <div class="form-group">
            <label for="variant-name">Variant name</label>
            <input id="variant-name" name="name" value="{{ old('name', $variant->name) }}" placeholder="256GB Natural Titanium" required>
        </div>
        <div class="form-group">
            <label for="variant-color">Color</label>
            <input id="variant-color" name="color" value="{{ old('color', $variant->color) }}">
        </div>
        <div class="form-group">
            <label for="variant-storage">Storage / size</label>
            <input id="variant-storage" name="storage" value="{{ old('storage', $variant->storage) }}">
        </div>
        <div class="form-group">
            <label for="variant-price">Selling price (Rs.)</label>
            <input id="variant-price" type="number" min="1" name="price" value="{{ old('price', $variant->price) }}" required>
        </div>
        <div class="form-group">
            <label for="variant-stock">Live stock</label>
            <input id="variant-stock" type="number" min="0" name="stock" value="{{ old('stock', $variant->stock ?? 0) }}" required>
        </div>
        <div class="form-group">
            <label for="variant-state">Visibility</label>
            <select id="variant-state" name="is_active">
                <option value="1" @selected((string) old('is_active', $variant->exists ? (int) $variant->is_active : 1) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', $variant->exists ? (int) $variant->is_active : 1) === '0')>Hidden</option>
            </select>
        </div>
    </div>
    <button class="btn btn-primary" type="submit" data-loading-text="Saving variant...">Save variant</button>
</form>
@endsection
