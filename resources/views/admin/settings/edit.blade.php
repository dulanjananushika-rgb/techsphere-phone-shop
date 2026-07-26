@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<h1>Settings</h1>
<form class="card card-body" method="POST" action="{{ route('admin.settings.update') }}">@csrf @method('PUT')
<div class="form-group"><label>WhatsApp Number</label><input name="whatsapp_number" value="{{ old('whatsapp_number', $whatsapp) }}" required></div>
<div class="form-group"><label>Shop Email</label><input name="shop_email" type="email" value="{{ old('shop_email', $email) }}" required></div>
<button class="btn btn-primary">Save settings</button></form>
@endsection
