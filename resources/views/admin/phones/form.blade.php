@extends('layouts.admin')
@section('title', $phone->exists ? 'Edit Phone' : 'Add Phone')

@section('content')
<div class="toolbar">
    <div>
        <h1>{{ $phone->exists ? 'Edit phone' : 'Add phone' }}</h1>
        <p>Publish product details, pricing, imagery, and fallback stock.</p>
    </div>
    <a class="btn" href="{{ route('admin.phones.index') }}">Back to phones</a>
</div>

<form class="card card-body"
      method="POST"
      enctype="multipart/form-data"
      data-lock-submit
      action="{{ $phone->exists ? route('admin.phones.update', $phone) : route('admin.phones.store') }}">
    @csrf
    @if($phone->exists) @method('PUT') @endif

    <div class="grid grid-2">
        <div class="form-group">
            <label for="phone-name">Product name</label>
            <input id="phone-name" name="name" value="{{ old('name', $phone->name) }}" required>
        </div>
        <div class="form-group">
            <label for="phone-brand">Brand</label>
            <select id="phone-brand" name="brand_id" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(old('brand_id', $phone->brand_id) == $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="phone-price">Base price (Rs.)</label>
            <input id="phone-price" type="number" min="1" name="price" value="{{ old('price', $phone->price) }}" required>
            <p class="field-help">Variant prices override this price when an SKU is selected.</p>
        </div>
        <div class="form-group">
            <label for="phone-old-price">Previous price (Rs.)</label>
            <input id="phone-old-price" type="number" min="0" name="old_price" value="{{ old('old_price', $phone->old_price) }}">
        </div>
        <div class="form-group">
            <label for="phone-stock">Base stock</label>
            <input id="phone-stock" type="number" min="0" name="stock" value="{{ old('stock', $phone->stock ?? 0) }}" required>
            <p class="field-help">Used only when this phone has no variants. Variant stock is the live total otherwise.</p>
        </div>
        <div class="form-group">
            <label for="phone-state">Store visibility</label>
            <select id="phone-state" name="is_active">
                <option value="1" @selected((string) old('is_active', $phone->exists ? (int) $phone->is_active : 1) === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', $phone->exists ? (int) $phone->is_active : 1) === '0')>Hidden</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone-ram">RAM</label>
            <input id="phone-ram" name="ram" value="{{ old('ram', $phone->ram) }}" placeholder="8GB" required>
        </div>
        <div class="form-group">
            <label for="phone-storage">Default storage</label>
            <input id="phone-storage" name="storage" value="{{ old('storage', $phone->storage) }}" placeholder="256GB" required>
        </div>
        <div class="form-group">
            <label for="phone-display">Display</label>
            <input id="phone-display" name="display" value="{{ old('display', $phone->display) }}">
        </div>
        <div class="form-group">
            <label for="phone-processor">Processor</label>
            <input id="phone-processor" name="processor" value="{{ old('processor', $phone->processor) }}">
        </div>
        <div class="form-group">
            <label for="phone-camera">Camera</label>
            <input id="phone-camera" name="camera" value="{{ old('camera', $phone->camera) }}">
        </div>
        <div class="form-group">
            <label for="phone-battery">Battery</label>
            <input id="phone-battery" name="battery" value="{{ old('battery', $phone->battery) }}">
        </div>
        <div class="form-group">
            <label for="phone-os">Operating system</label>
            <input id="phone-os" name="os" value="{{ old('os', $phone->os) }}">
        </div>
        <div class="form-group">
            <label for="phone-featured">Featured placement</label>
            <select id="phone-featured" name="is_featured">
                <option value="0" @selected((string) old('is_featured', (int) $phone->is_featured) === '0')>Standard catalog</option>
                <option value="1" @selected((string) old('is_featured', (int) $phone->is_featured) === '1')>Feature on home page</option>
            </select>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="form-group">
            <label for="phone-image-file">Upload product image</label>
            <input id="phone-image-file" type="file" name="image_file" accept=".jpg,.jpeg,.png,.webp" data-image-input="#phone-image-preview">
            <p class="field-help">JPG, PNG, or WebP up to 4MB. Uploading replaces the current image.</p>
            <img id="phone-image-preview" class="image-preview" src="{{ $phone->image_url }}" alt="Product image preview" @if(!$phone->image_url) hidden @endif>
        </div>
        <div class="form-group">
            <label for="phone-image-url">Or external image URL</label>
            <input id="phone-image-url" name="image_url" value="{{ old('image_url', $phone->image_url) }}" placeholder="https://...">
        </div>
    </div>

    <div class="form-group">
        <label for="phone-description">Customer-facing description</label>
        <textarea id="phone-description" name="description">{{ old('description', $phone->description) }}</textarea>
    </div>

    <button class="btn btn-primary" type="submit" data-loading-text="Saving phone...">Save phone</button>
</form>
@endsection
