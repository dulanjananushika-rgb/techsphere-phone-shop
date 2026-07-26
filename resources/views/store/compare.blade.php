@extends('layouts.app')
@section('title', 'Compare Phones | TechSphere')

@section('content')
<section class="section compare-hero">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Compare models</div>
                <h2>Compare up to three phones</h2>
                <p>Choose the models you are considering and check the key buying details together.</p>
            </div>
        </div>

        <form method="GET" class="compare-picker">
            <div class="compare-toolbar">
                <div>
                    <strong>{{ $selectedIds->count() }}/3 selected</strong>
                    <p class="muted">Tick one to three models, then press Compare selected.</p>
                </div>
                <div class="actions">
                    <a class="btn" href="{{ route('compare') }}">Clear</a>
                    <button class="btn btn-primary" type="submit">Compare selected</button>
                </div>
            </div>

            <div class="compare-options">
                @foreach($allPhones as $phone)
                    <label class="compare-option">
                        <input type="checkbox" name="phones[]" value="{{ $phone->id }}" @checked($selectedIds->contains($phone->id))>
                        <img src="{{ $phone->image_url }}" alt="{{ $phone->name }}">
                        <span>
                            <strong>{{ $phone->name }}</strong>
                            <small>{{ $phone->brand->name }} - Rs. {{ number_format($phone->price) }}</small>
                        </span>
                    </label>
                @endforeach
            </div>
        </form>

        @if($phones->count())
            <div class="compare-result">
                <table class="compare-table">
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
                    @foreach(['sale_price' => 'Current Price', 'ram' => 'RAM', 'storage' => 'Storage', 'display' => 'Display', 'processor' => 'Processor', 'camera' => 'Camera', 'battery' => 'Battery', 'os' => 'OS'] as $key => $label)
                        <tr>
                            <th>{{ $label }}</th>
                            @foreach($phones as $phone)
                                <td>{{ $key === 'sale_price' ? 'Rs. '.number_format($phone->salePrice()) : data_get($phone, $key) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            <div class="empty-state">Select phones above to start a comparison.</div>
        @endif
    </div>
</section>
@endsection
