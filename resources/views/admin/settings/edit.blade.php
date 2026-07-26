@extends('layouts.admin')
@section('title', 'Store Settings')

@section('content')
<div class="toolbar">
    <div>
        <h1>Store settings</h1>
        <p>These details are used across checkout, invoices, contact links, and emails.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" data-lock-submit>
    @csrf
    @method('PUT')

    <div class="grid grid-2">
        <section class="card card-body">
            <h2>Store profile</h2>
            <div class="form-group">
                <label for="setting-store-name">Store name</label>
                <input id="setting-store-name" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required>
            </div>
            <div class="form-group">
                <label for="setting-email">Shop email</label>
                <input id="setting-email" name="shop_email" type="email" value="{{ old('shop_email', $settings['shop_email']) }}" required>
            </div>
            <div class="form-group">
                <label for="setting-phone">Shop phone</label>
                <input id="setting-phone" name="shop_phone" value="{{ old('shop_phone', $settings['shop_phone']) }}" required>
            </div>
            <div class="form-group">
                <label for="setting-whatsapp">WhatsApp number</label>
                <input id="setting-whatsapp" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" required>
                <p class="field-help">Use international digits without spaces, for example 94771234567.</p>
            </div>
            <div class="form-group">
                <label for="setting-address">Shop address</label>
                <textarea id="setting-address" name="shop_address" required>{{ old('shop_address', $settings['shop_address']) }}</textarea>
            </div>
            <div class="form-group">
                <label for="setting-hours">Opening hours</label>
                <input id="setting-hours" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours']) }}" required>
            </div>
        </section>

        <section class="card card-body">
            <h2>Checkout and payment</h2>
            <div class="grid grid-2">
                <div class="form-group">
                    <label for="setting-delivery">Delivery fee (Rs.)</label>
                    <input id="setting-delivery" type="number" min="0" name="delivery_fee" value="{{ old('delivery_fee', $settings['delivery_fee']) }}" required>
                </div>
                <div class="form-group">
                    <label for="setting-reservation">Reservation hours</label>
                    <input id="setting-reservation" type="number" min="1" max="168" name="reservation_hours" value="{{ old('reservation_hours', $settings['reservation_hours']) }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="setting-bank">Bank name</label>
                <input id="setting-bank" name="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}">
            </div>
            <div class="form-group">
                <label for="setting-account-name">Account name</label>
                <input id="setting-account-name" name="bank_account_name" value="{{ old('bank_account_name', $settings['bank_account_name']) }}">
            </div>
            <div class="form-group">
                <label for="setting-account-number">Account number</label>
                <input id="setting-account-number" name="bank_account_number" value="{{ old('bank_account_number', $settings['bank_account_number']) }}">
            </div>
            <p class="field-help">Bank details appear only when a customer chooses bank transfer.</p>
        </section>
    </div>

    <button class="btn btn-primary" style="margin-top:18px" type="submit" data-loading-text="Saving settings...">Save settings</button>
</form>
@endsection
