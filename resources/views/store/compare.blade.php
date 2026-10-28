@extends('layouts.app')
@section('title', 'Flagship Comparison Matrix | '.$shopSettings['store_name'])
@section('meta_description', 'Compare technical specifications, optics, processors, battery capacities, and prices across leading smartphones side-by-side.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Direct Benchmark</div>
                <h1>Compare Technical Specs</h1>
                <p>Select up to three smartphones side-by-side to review exact differences.</p>
            </div>
        </div>

        <form method="GET" class="compare-picker" data-compare-form>
            <div class="compare-toolbar">
                <div>
                    <strong style="font-size:1.1rem;color:#ffffff"><span data-compare-count style="color:var(--titanium-gold)">{{ $selectedIds->count() }}</span> / 3 Devices Selected</strong>
                    <p class="muted" style="margin-top:2px">Prices reflect active promotional offers.</p>
                </div>
                <div class="actions">
                    <a class="btn" href="{{ route('compare') }}">Clear All</a>
                    <button class="btn btn-primary" type="submit" data-compare-submit>
                        <span>⚖️ Compare Models</span>
                    </button>
                </div>
            </div>

            <div class="compare-options">
                @foreach($allPhones as $phone)
                    <label class="compare-option">
                        <input type="checkbox" name="phones[]" value="{{ $phone->id }}" @checked($selectedIds->contains($phone->id))>
                        <img src="{{ $phone->image_url }}" alt="" loading="lazy">
                        <span>
                            <strong>{{ $phone->name }}</strong>
                            <small style="color:var(--text-secondary)">{{ $phone->brand->name }} &bull; <span style="color:var(--titanium-gold)">Rs. {{ number_format($phone->salePrice()) }}</span></small>
                        </span>
                    </label>
                @endforeach
            </div>
            <p class="compare-warning" data-compare-warning hidden>⚡ Maximum of 3 devices selected. Uncheck one to choose another model.</p>
        </form>

        @if($phones->count())
            <div class="table-wrap" style="box-shadow:var(--shadow-card)">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th style="min-width:180px">Hardware Matrix</th>
                            @foreach($phones as $phone)
                                <th style="text-align:center;min-width:220px">
                                    <img src="{{ $phone->image_url }}" alt="{{ $phone->name }}" style="margin:0 auto 12px;filter:drop-shadow(0 10px 20px rgba(0,0,0,0.6))">
                                    <strong style="font-size:1.15rem;display:block;color:#ffffff">{{ $phone->name }}</strong>
                                    <span style="color:var(--titanium-gold);font-weight:700;font-size:0.8rem;text-transform:uppercase">{{ $phone->brand->name }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            'sale_price' => 'Verified Price',
                            'ram' => 'System Memory (RAM)',
                            'storage' => 'Internal Storage',
                            'display' => 'Screen & Display',
                            'processor' => 'SoC Chipset',
                            'camera' => 'Camera Architecture',
                            'battery' => 'Battery & Power',
                            'os' => 'Software Platform'
                        ] as $key => $label)
                            <tr>
                                <th style="color:var(--text-secondary);font-weight:700">{{ $label }}</th>
                                @foreach($phones as $phone)
                                    <td style="text-align:center;font-weight:600">
                                        @if($key === 'sale_price')
                                            <span style="font-size:1.2rem;font-weight:900;color:var(--sapphire-light)">Rs. {{ number_format($phone->salePrice()) }}</span>
                                        @else
                                            {{ data_get($phone, $key) ?: 'Not listed' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <th>Direct Action</th>
                            @foreach($phones as $phone)
                                <td style="text-align:center">
                                    <a class="btn btn-primary btn-small btn-block" href="{{ route('orders.phone', $phone) }}">
                                        <span>Reserve Now</span>
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <p style="font-size:1.3rem;margin-bottom:8px">⚖️ Ready to compare?</p>
                <p class="muted">Check 1 to 3 phones above and click "Compare Models" to review side-by-side specs.</p>
            </div>
        @endif
    </div>
</section>
@endsection
