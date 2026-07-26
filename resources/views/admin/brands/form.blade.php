@extends('layouts.admin')
@section('title', $brand->exists ? 'Edit Brand' : 'Add Brand')
@section('content')
<h1>{{ $brand->exists ? 'Edit Brand' : 'Add Brand' }}</h1>
<form class="card card-body" method="POST" action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}">@csrf @if($brand->exists) @method('PUT') @endif
<div class="form-group"><label>Name</label><input name="name" value="{{ old('name', $brand->name) }}" required></div>
<div class="form-group"><label>Logo URL</label><input name="logo_url" value="{{ old('logo_url', $brand->logo_url) }}"></div>
<div class="form-group"><label>Description</label><textarea name="description">{{ old('description', $brand->description) }}</textarea></div>
<button class="btn btn-primary">Save brand</button></form>
@endsection
