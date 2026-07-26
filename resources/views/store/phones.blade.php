@extends('layouts.app')
@section('title', 'Phones | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Phone catalog</div>
                <h1>Find your next phone</h1>
                <p>Compare current pricing, specifications, and live stock.</p>
            </div>
        </div>

        <div class="shop-layout">
            <form class="filters" method="GET">
                <h3>Filter phones</h3>
                <div class="form-group">
                    <label for="catalog-search">Search</label>
                    <input id="catalog-search" name="search" value="{{ request('search') }}" placeholder="Model or brand">
                </div>
                <div class="form-group">
                    <label for="catalog-brand">Brand</label>
                    <select id="catalog-brand" name="brand">
                        <option value="">All brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="catalog-ram">RAM</label>
                    <select id="catalog-ram" name="ram">
                        <option value="">Any RAM</option>
                        @foreach($ramOptions as $ram)
                            <option value="{{ $ram }}" @selected(request('ram') == $ram)>{{ $ram }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="catalog-storage">Storage</label>
                    <select id="catalog-storage" name="storage">
                        <option value="">Any storage</option>
                        @foreach($storageOptions as $storage)
                            <option value="{{ $storage }}" @selected(request('storage') == $storage)>{{ $storage }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="catalog-price">Maximum price</label>
                    <input id="catalog-price" type="number" min="1" name="max_price" value="{{ request('max_price') }}" placeholder="500000">
                </div>
                <button class="btn btn-primary btn-block" type="submit">Apply filters</button>
                <a class="btn btn-block" style="margin-top:8px" href="{{ route('phones.index') }}">Clear filters</a>
            </form>

            <div class="catalog-results">
                <div class="catalog-count">{{ $phones->total() }} {{ Str::plural('phone', $phones->total()) }} found</div>
                <div class="grid grid-3">
                    @forelse($phones as $phone)
                        @include('store.partials.phone-card', ['phone' => $phone, 'whatsapp' => $whatsapp])
                    @empty
                        <div class="empty-state">No phones match those filters. Try clearing one or two options.</div>
                    @endforelse
                </div>
                <div class="pagination">{{ $phones->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
