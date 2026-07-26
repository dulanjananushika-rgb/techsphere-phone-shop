@extends('layouts.admin')
@section('title', $variant->exists ? 'Edit Variant' : 'Add Variant')

@section('content')
<div class="toolbar">
    <div><h1>{{ $variant->exists ? 'Edit Variant' : 'Add Variant' }}</h1><p class="muted">Track real stock by SKU, color, and storage.</p></div>
    <a class="btn" href="{{ route('admin.variants.index') }}">Back to variants</a>
</div>

@php
    $currentTarget = old('product_target');
    if (! $currentTarget && $variant->exists) {
        $currentTarget = ($variant->product_type === \App\Models\Phone::class ? 'phone' : 'accessory') . ':' . $variant->product_id;
    }
@endphp

<form class="card card-body" method="POST" action="{{ $variant->exists ? route('admin.variants.update', $variant) : route('admin.variants.store') }}">
    @csrf @if($variant->exists) @method('PUT') @endif
    <div class="grid grid-2">
        <div class="form-group">
            <label>Product</label>
            <select name="product_target" required>
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
        <div class="form-group"><label>SKU</label><input name="sku" value="{{ old('sku', $variant->sku) }}" required></div>
        <div class="form-group"><label>Variant name</label><input name="name" value="{{ old('name', $variant->name) }}" placeholder="256GB Natural Titanium" required></div>
        <div class="form-group"><label>Color</label><input name="color" value="{{ old('color', $variant->color) }}"></div>
        <div class="form-group"><label>Storage / Size</label><input name="storage" value="{{ old('storage', $variant->storage) }}"></div>
        <div class="form-group"><label>Price</label><input type="number" name="price" value="{{ old('price', $variant->price) }}" required></div>
        <div class="form-group"><label>Stock</label><input type="number" name="stock" value="{{ old('stock', $variant->stock ?? 0) }}" required></div>
        <div class="form-group"><label>Status</label><select name="is_active"><option value="1" @selected(old('is_active', $variant->is_active ?? true))>Active</option><option value="0" @selected(old('is_active', $variant->is_active ?? true) == false)>Hidden</option></select></div>
    </div>
    <button class="btn btn-primary">Save variant</button>
</form>
@endsection
