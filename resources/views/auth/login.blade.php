@extends('layouts.app')
@section('title', 'Customer Portal Login | '.$shopSettings['store_name'])

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-card">
            <div class="eyebrow">Customer Security Portal</div>
            <h1>Welcome Back</h1>
            <p class="muted" style="margin-bottom:24px">Access your real-time reservations, wishlist, and warranty records.</p>
            
            <form method="POST" action="{{ route('login.store') }}" data-lock-submit>
                @csrf
                <div class="form-group">
                    <label for="login-email">Registered Email Address</label>
                    <input id="login-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="name@example.com">
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="login-password">Account Password</label>
                    <input id="login-password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••">
                </div>
                <button class="btn btn-primary auth-submit" type="submit" data-loading-text="Authenticating securely...">
                    <span>🔐 Access Account</span>
                </button>
            </form>
            <p class="muted" style="margin-bottom:0;text-align:center;font-size:0.92rem">
                New to TechSphere? <a href="{{ route('register') }}" style="color:var(--sapphire-light);font-weight:800">Create an Account &rarr;</a>
            </p>
        </div>
    </div>
</section>
@endsection
