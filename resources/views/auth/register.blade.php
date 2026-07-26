@extends('layouts.app')
@section('title', 'Create Account | '.$shopSettings['store_name'])

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-card">
            <div class="eyebrow">Customer account</div>
            <h1>Create your account</h1>
            <p class="muted">Save phones and keep your order history in one place.</p>
            <form method="POST" action="{{ route('register.store') }}" data-lock-submit>
                @csrf
                <div class="form-group">
                    <label for="register-name">Full name</label>
                    <input id="register-name" name="name" value="{{ old('name') }}" autocomplete="name" required>
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="register-email">Email address</label>
                    <input id="register-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input id="register-password" name="password" type="password" autocomplete="new-password" required>
                    @error('password')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="register-password-confirmation">Confirm password</label>
                    <input id="register-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                </div>
                <button class="btn btn-primary auth-submit" type="submit" data-loading-text="Creating account...">Create account</button>
            </form>
            <p class="muted" style="margin-bottom:0">Already registered? <a href="{{ route('login') }}" style="color:var(--brand-dark);font-weight:800">Log in</a></p>
        </div>
    </div>
</section>
@endsection
