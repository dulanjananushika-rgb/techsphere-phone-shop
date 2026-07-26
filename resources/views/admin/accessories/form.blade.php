@extends('layouts.admin')
@section('title', $accessory->exists ? 'Edit Accessory' : 'Add Accessory')

@section('content')
<div class="toolbar">
    <div><h1>{{ $accessory->exists ? 'Edit accessory' : 'Add accessory' }}</h1><p>Manage price, imagery, visibility, and fallback stock.</p></div>
    <a class="btn" href="{{ route('admin.accessories.index') }}">Back to accessories</a>
</div>

<form class="card card-body"
      method="POST"
      enctype="multipart/form-data"
      data-lock-submit
      action="{{ $accessory->exists ? route('admin.accessories.update', $accessory) : route('admin.accessories.store') }}">
    @csrf
    @if($accessory->exists) @method('PUT') @endif
    <div class="grid grid-2">
        <div class="form-group">
            <label for="accessory-name">Product name</label>
            <input id="accessory-name" name="name" value="{{ old('name', $accessory->name) }}" required>
        </div>
        <div class="form-group">
            <label for="accessory-category">Category</label>
            <input id="accessory-category" name="category" value="{{ old('category', $accessory->category) }}" placeholder="Chargers" required>
        </div>
        <div class="form-group">
            <label for="accessory-price">Base price (Rs.)</label>
            <input id="accessory-price" type="number" min="1" name="price" value="{{ old('price', $accessory->price) }}" required>
        </div>
        <div class="form-group">
            <label for="accessory-stock">Base stock</label>
            <input id="accessory-stock" type="number" min="0" name="stock" value="{{ old('stock', $accessory->stock ?? 0) }}" required>
            <p class="field-help">Used only when this product has no variants.</p>
        </div>
        <div class="form-group">
            <label for="accessory-state">Store visibility</label>
            <select id="accessory-state" name="is_active">
                <option value="1" @selected((string) old('is_active', $accessory->exists ? (int) $accessory->is_active : 1) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', $accessory->exists ? (int) $accessory->is_active : 1) === '0')>Hidden</option>
            </select>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="form-group">
            <label for="accessory-image-file">Upload product image</label>
            <input id="accessory-image-file" type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp" data-image-input="#accessory-image-preview">
            <img id="accessory-image-preview" class="image-preview" src="{{ $accessory->image_url }}" alt="Product image preview" @if(!$accessory->image_url) hidden @endif>
        </div>
        <div class="form-group">
            <label for="accessory-image-url">Or external image URL</label>
            <input id="accessory-image-url" name="image_url" value="{{ old('image_url', $accessory->image_url) }}" placeholder="https://...">
        </div>
    </div>

    <div class="form-group">
        <label for="accessory-description">Customer-facing description</label>
        <textarea id="accessory-description" name="description">{{ old('description', $accessory->description) }}</textarea>
    </div>
    <button class="btn btn-primary" type="submit" data-loading-text="Saving accessory...">Save accessory</button>
</form>
@endsection
