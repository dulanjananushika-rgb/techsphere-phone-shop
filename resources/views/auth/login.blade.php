@extends('layouts.app')
@section('title', 'Login | TechSphere')

@section('content')
<div class="container">
    <div class="auth-card">
        <h1>Login</h1>
        <p class="muted">Demo admin: admin@techsphere.test / password</p>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="form-group"><label>Email</label><input name="email" type="email" value="{{ old('email') }}" required>@error('email')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label>Password</label><input name="password" type="password" required></div>
            <button class="btn btn-primary auth-submit" type="submit">Login</button>
        </form>
    </div>
</div>
@endsection
