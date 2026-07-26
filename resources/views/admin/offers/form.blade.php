@extends('layouts.admin')
@section('title', $offer->exists ? 'Edit Offer' : 'Add Offer')
@section('content')
<div class="toolbar">
    <div>
        <h1>{{ $offer->exists ? 'Edit Offer' : 'Create Offer' }}</h1>
        <p class="muted">Published active offers appear on the storefront automatically.</p>
    </div>
    <a class="btn" href="{{ route('admin.offers.index') }}">Back to offers</a>
</div>
<form class="card card-body" method="POST" action="{{ $offer->exists ? route('admin.offers.update', $offer) : route('admin.offers.store') }}">
    @csrf @if($offer->exists) @method('PUT') @endif
    <div class="grid grid-2">
        <div class="form-group"><label>Campaign title</label><input name="title" value="{{ old('title', $offer->title) }}" placeholder="Back to Campus Deals" required></div>
        <div class="form-group"><label>Discount percentage</label><input type="number" min="1" max="90" name="discount_percentage" value="{{ old('discount_percentage', $offer->discount_percentage) }}" required></div>
        <div class="form-group"><label>Start date</label><input type="date" name="starts_at" value="{{ old('starts_at', optional($offer->starts_at)->format('Y-m-d')) }}" required></div>
        <div class="form-group"><label>End date</label><input type="date" name="ends_at" value="{{ old('ends_at', optional($offer->ends_at)->format('Y-m-d')) }}" required></div>
    </div>
    <div class="form-group"><label>Customer-facing description</label><textarea name="description" placeholder="Short, clear offer message customers will see.">{{ old('description', $offer->description) }}</textarea></div>
    <div class="grid grid-2">
        <div class="form-group">
            <label>Apply to phones</label>
            <div class="check-list">
                @foreach($phones as $phone)
                    <label class="check-row">
                        <input type="checkbox" name="phone_ids[]" value="{{ $phone->id }}" @checked(collect(old('phone_ids', $selectedPhones))->contains($phone->id))>
                        <span>{{ $phone->name }} <small>{{ $phone->brand->name }}</small></span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label>Apply to accessories</label>
            <div class="check-list">
                @foreach($accessories as $accessory)
                    <label class="check-row">
                        <input type="checkbox" name="accessory_ids[]" value="{{ $accessory->id }}" @checked(collect(old('accessory_ids', $selectedAccessories))->contains($accessory->id))>
                        <span>{{ $accessory->name }} <small>{{ $accessory->category }}</small></span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
    <button class="btn btn-primary">Save offer</button>
</form>
@endsection
