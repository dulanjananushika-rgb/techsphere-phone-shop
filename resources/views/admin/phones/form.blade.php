@extends('layouts.admin')
@section('title', $phone->exists ? 'Edit Phone' : 'Add Phone')

@section('content')
<div class="toolbar"><div><h1>{{ $phone->exists ? 'Edit Phone' : 'Add Phone' }}</h1><p class="muted">Add device details, pricing, stock, and product imagery.</p></div></div>
<form class="card card-body" method="POST" action="{{ $phone->exists ? route('admin.phones.update', $phone) : route('admin.phones.store') }}">
    @csrf @if($phone->exists) @method('PUT') @endif
    <div class="grid grid-2">
        <div class="form-group"><label>Name</label><input name="name" value="{{ old('name', $phone->name) }}" required></div>
        <div class="form-group"><label>Brand</label><select name="brand_id" required>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $phone->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
        <div class="form-group"><label>Price</label><input type="number" name="price" value="{{ old('price', $phone->price) }}" required></div>
        <div class="form-group"><label>Old Price</label><input type="number" name="old_price" value="{{ old('old_price', $phone->old_price) }}"></div>
        <div class="form-group"><label>Image URL</label><input name="image_url" value="{{ old('image_url', $phone->image_url) }}" required></div>
        <div class="form-group"><label>Stock</label><input type="number" name="stock" value="{{ old('stock', $phone->stock ?? 0) }}" required></div>
        <div class="form-group"><label>RAM</label><input name="ram" value="{{ old('ram', $phone->ram) }}" required></div>
        <div class="form-group"><label>Storage</label><input name="storage" value="{{ old('storage', $phone->storage) }}" required></div>
        <div class="form-group"><label>Display</label><input name="display" value="{{ old('display', $phone->display) }}"></div>
        <div class="form-group"><label>Processor</label><input name="processor" value="{{ old('processor', $phone->processor) }}"></div>
        <div class="form-group"><label>Camera</label><input name="camera" value="{{ old('camera', $phone->camera) }}"></div>
        <div class="form-group"><label>Battery</label><input name="battery" value="{{ old('battery', $phone->battery) }}"></div>
        <div class="form-group"><label>OS</label><input name="os" value="{{ old('os', $phone->os) }}"></div>
        <div class="form-group"><label>Featured</label><select name="is_featured"><option value="0">No</option><option value="1" @selected(old('is_featured', $phone->is_featured) == 1)>Yes</option></select></div>
    </div>
    <div class="form-group"><label>Description</label><textarea name="description">{{ old('description', $phone->description) }}</textarea></div>
    <button class="btn btn-primary">Save phone</button>
</form>
@endsection
