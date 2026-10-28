@extends('layouts.app')
@section('title', 'Create Customer Account | '.$shopSettings['store_name'])

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-card">
            <div class="eyebrow">Membership Portal</div>
            <h1>Create Account</h1>
            <p class="muted" style="margin-bottom:24px">Track your hardware reservations, save wishlists, and receive fast order support.</p>
            
            <form method="POST" action="{{ route('register.store') }}" data-lock-submit>
                @csrf
                <div class="form-group">
                    <label for="register-name">Full Legal Name</label>
                    <input id="register-name" name="name" value="{{ old('name') }}" autocomplete="name" required placeholder="Dulanjan Anushika">
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="register-email">Email Address</label>
                    <input id="register-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required placeholder="name@example.com">
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="register-password">Password (Minimum 8 Characters with Numbers)</label>
                    <input id="register-password" name="password" type="password" autocomplete="new-password" required placeholder="••••••••">
                    @error('password')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="register-password-confirmation">Confirm Password</label>
                    <input id="register-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" required placeholder="••••••••">
                </div>
                <button class="btn btn-primary auth-submit" type="submit" data-loading-text="Registering Account...">
                    <span>⚡ Create My Account</span>
                </button>
            </form>
            <p class="muted" style="margin-bottom:0;text-align:center;font-size:0.92rem">
                Already registered? <a href="{{ route('login') }}" style="color:var(--sapphire-light);font-weight:800">Log In Here &rarr;</a>
            </p>
        </div>
    </div>
</section>
@endsection
