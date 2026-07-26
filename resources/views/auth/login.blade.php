@extends('layouts.app')
@section('title', 'Log In | '.$shopSettings['store_name'])

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-card">
            <div class="eyebrow">Customer account</div>
            <h1>Welcome back</h1>
            <p class="muted">Log in to view your orders and saved phones.</p>
            <form method="POST" action="{{ route('login.store') }}" data-lock-submit>
                @csrf
                <div class="form-group">
                    <label for="login-email">Email address</label>
                    <input id="login-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input id="login-password" name="password" type="password" autocomplete="current-password" required>
                </div>
                <button class="btn btn-primary auth-submit" type="submit" data-loading-text="Logging in...">Log in</button>
            </form>
            <p class="muted" style="margin-bottom:0">New customer? <a href="{{ route('register') }}" style="color:var(--brand-dark);font-weight:800">Create an account</a></p>
        </div>
    </div>
</section>
@endsection
