@extends('layouts.admin')
@section('title', $brand->exists ? 'Edit Brand' : 'Add Brand')

@section('content')
<div class="toolbar">
    <div><h1>{{ $brand->exists ? 'Edit brand' : 'Add brand' }}</h1><p>Manage manufacturer information for catalog grouping.</p></div>
    <a class="btn" href="{{ route('admin.brands.index') }}">Back to brands</a>
</div>
<form class="card card-body" method="POST" action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" data-lock-submit>
    @csrf
    @if($brand->exists) @method('PUT') @endif
    <div class="form-group">
        <label for="brand-name">Brand name</label>
        <input id="brand-name" name="name" value="{{ old('name', $brand->name) }}" required>
    </div>
    <div class="form-group">
        <label for="brand-logo">Logo URL</label>
        <input id="brand-logo" name="logo_url" value="{{ old('logo_url', $brand->logo_url) }}" placeholder="https://...">
    </div>
    <div class="form-group">
        <label for="brand-description">Description</label>
        <textarea id="brand-description" name="description">{{ old('description', $brand->description) }}</textarea>
    </div>
    <button class="btn btn-primary" type="submit" data-loading-text="Saving brand...">Save brand</button>
</form>
@endsection
