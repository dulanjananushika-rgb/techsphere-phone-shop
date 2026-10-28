@extends('layouts.app')
@section('title', 'Smartphone Catalog | '.$shopSettings['store_name'])
@section('meta_description', 'Explore Sri Lanka\'s most comprehensive genuine smartphone inventory with live stock filters, verified warranty, and atomic reservations.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Interactive Inventory</div>
                <h1>Discover Flagship Devices</h1>
                <p>Filter by brand, processing memory, internal storage, or budget threshold.</p>
            </div>
            @if(request()->anyFilled(['search', 'brand', 'ram', 'storage', 'max_price']))
                <a class="btn btn-small" href="{{ route('phones.index') }}">Reset All Filters</a>
            @endif
        </div>

        <div class="shop-layout">
            <!-- Clean Control Panel Sidebar -->
            <form class="filters" method="GET" action="{{ route('phones.index') }}">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--border-subtle)">
                    <h3 style="margin:0;font-size:1.15rem">Filter Catalog</h3>
                    <span style="font-size:0.75rem;font-weight:800;color:var(--titanium-gold);text-transform:uppercase">Active Control</span>
                </div>

                <div class="form-group">
                    <label for="catalog-search">Keyword Search</label>
                    <input id="catalog-search" name="search" value="{{ request('search') }}" placeholder="Search model or brand...">
                </div>

                <div class="form-group">
                    <label for="catalog-brand">Brand Manufacturer</label>
                    <select id="catalog-brand" name="brand">
                        <option value="">All Leading Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="catalog-ram">Memory (RAM)</label>
                    <select id="catalog-ram" name="ram">
                        <option value="">Any RAM Configuration</option>
                        @foreach($ramOptions as $ram)
                            <option value="{{ $ram }}" @selected(request('ram') == $ram)>{{ $ram }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="catalog-storage">Internal Storage</label>
                    <select id="catalog-storage" name="storage">
                        <option value="">Any Storage Tier</option>
                        @foreach($storageOptions as $storage)
                            <option value="{{ $storage }}" @selected(request('storage') == $storage)>{{ $storage }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="catalog-price">Max Budget (LKR)</label>
                    <input id="catalog-price" type="number" min="1" name="max_price" value="{{ request('max_price') }}" placeholder="e.g. 500000">
                </div>

                <button class="btn btn-primary btn-block" type="submit">
                    <span>⚡ Apply Parameters</span>
                </button>
                <a class="btn btn-block" style="margin-top:10px" href="{{ route('phones.index') }}">Clear Filters</a>
            </form>

            <!-- Results Grid -->
            <div class="catalog-results">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px">
                    <div class="catalog-count" style="margin-bottom:0">
                        <span style="color:#ffffff;font-size:1.15rem">{{ $phones->total() }}</span> {{ Str::plural('smartphone', $phones->total()) }} available for reservation
                    </div>
                </div>

                <div class="grid grid-3">
                    @forelse($phones as $phone)
                        @include('store.partials.phone-card', ['phone' => $phone, 'whatsapp' => $whatsapp])
                    @empty
                        <div class="empty-state">
                            <p style="font-size:1.4rem;margin-bottom:12px">🔍 No exact matches found</p>
                            <p class="muted">Try adjusting your budget or clearing brand and RAM filters to view more options.</p>
                            <a class="btn btn-primary" style="margin-top:16px" href="{{ route('phones.index') }}">Reset Catalog</a>
                        </div>
                    @endforelse
                </div>

                <div class="pagination">{{ $phones->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
