@extends('layouts.app')
@section('title', 'Compare Phones | '.$shopSettings['store_name'])

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Side-by-side</div>
                <h1>Compare phones</h1>
                <p>Select one to three models. The fourth option stays unavailable until you remove a selection.</p>
            </div>
        </div>

        <form method="GET" class="compare-picker" data-compare-form>
            <div class="compare-toolbar">
                <div>
                    <strong><span data-compare-count>{{ $selectedIds->count() }}</span>/3 selected</strong>
                    <p class="muted">Prices below include active store offers.</p>
                </div>
                <div class="actions">
                    <a class="btn" href="{{ route('compare') }}">Clear</a>
                    <button class="btn btn-primary" type="submit" data-compare-submit>Compare selected</button>
                </div>
            </div>

            <div class="compare-options">
                @foreach($allPhones as $phone)
                    <label class="compare-option">
                        <input type="checkbox" name="phones[]" value="{{ $phone->id }}" @checked($selectedIds->contains($phone->id))>
                        <img src="{{ $phone->image_url }}" alt="" loading="lazy">
                        <span>
                            <strong>{{ $phone->name }}</strong>
                            <small>{{ $phone->brand->name }} | Rs. {{ number_format($phone->salePrice()) }}</small>
                        </span>
                    </label>
                @endforeach
            </div>
            <p class="compare-warning" data-compare-warning hidden>Three phones selected. Remove one to choose another.</p>
        </form>

        @if($phones->count())
            <div class="compare-result">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Specification</th>
                            @foreach($phones as $phone)
                                <th>
                                    <img src="{{ $phone->image_url }}" alt="{{ $phone->name }}">
                                    <strong>{{ $phone->name }}</strong>
                                    <span>{{ $phone->brand->name }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['sale_price' => 'Current price', 'ram' => 'RAM', 'storage' => 'Storage', 'display' => 'Display', 'processor' => 'Processor', 'camera' => 'Camera', 'battery' => 'Battery', 'os' => 'OS'] as $key => $label)
                            <tr>
                                <th>{{ $label }}</th>
                                @foreach($phones as $phone)
                                    <td>{{ $key === 'sale_price' ? 'Rs. '.number_format($phone->salePrice()) : (data_get($phone, $key) ?: 'Not listed') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <th>Action</th>
                            @foreach($phones as $phone)
                                <td><a class="btn btn-primary btn-small" href="{{ route('orders.phone', $phone) }}">Reserve</a></td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">Choose at least one phone above to start comparing.</div>
        @endif
    </div>
</section>
@endsection
