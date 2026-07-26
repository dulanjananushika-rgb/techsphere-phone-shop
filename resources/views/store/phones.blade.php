@extends('layouts.app')
@section('title', 'Phones | TechSphere')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head"><div><h2>Phones</h2><p>Filter by brand, memory, storage, and budget.</p></div></div>
        <div class="shop-layout">
            <form class="filters" method="GET">
                <h3>Find a phone</h3>
                <div class="form-group"><label>Search</label><input name="search" value="{{ request('search') }}" placeholder="iPhone, Samsung..."></div>
                <div class="form-group"><label>Brand</label><select name="brand"><option value="">All brands</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
                <div class="form-group"><label>RAM</label><select name="ram"><option value="">Any RAM</option>@foreach($ramOptions as $ram)<option @selected(request('ram') == $ram)>{{ $ram }}</option>@endforeach</select></div>
                <div class="form-group"><label>Storage</label><select name="storage"><option value="">Any storage</option>@foreach($storageOptions as $storage)<option @selected(request('storage') == $storage)>{{ $storage }}</option>@endforeach</select></div>
                <div class="form-group"><label>Max price</label><input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="500000"></div>
                <button class="btn btn-primary" style="width:100%">Apply filters</button>
                <a class="btn" style="width:100%;margin-top:8px" href="{{ route('phones.index') }}">Clear</a>
            </form>
            <div class="catalog-results">
                <div class="grid grid-3">
                    @forelse($phones as $phone)
                        @include('store.partials.phone-card', ['phone' => $phone, 'whatsapp' => $whatsapp])
                    @empty
                        <div class="card card-body">No matching phones found.</div>
                    @endforelse
                </div>
                <div style="margin-top:18px">{{ $phones->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
