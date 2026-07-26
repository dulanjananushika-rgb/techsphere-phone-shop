@extends('layouts.admin')
@section('title', $accessory->exists ? 'Edit Accessory' : 'Add Accessory')
@section('content')
<h1>{{ $accessory->exists ? 'Edit Accessory' : 'Add Accessory' }}</h1>
<form class="card card-body" method="POST" action="{{ $accessory->exists ? route('admin.accessories.update', $accessory) : route('admin.accessories.store') }}">@csrf @if($accessory->exists) @method('PUT') @endif
<div class="grid grid-2"><div class="form-group"><label>Name</label><input name="name" value="{{ old('name', $accessory->name) }}" required></div><div class="form-group"><label>Category</label><input name="category" value="{{ old('category', $accessory->category) }}" required></div><div class="form-group"><label>Price</label><input type="number" name="price" value="{{ old('price', $accessory->price) }}" required></div><div class="form-group"><label>Stock</label><input type="number" name="stock" value="{{ old('stock', $accessory->stock ?? 0) }}" required></div></div>
<div class="form-group"><label>Image URL</label><input name="image_url" value="{{ old('image_url', $accessory->image_url) }}" required></div><div class="form-group"><label>Description</label><textarea name="description">{{ old('description', $accessory->description) }}</textarea></div><button class="btn btn-primary">Save accessory</button></form>
@endsection
