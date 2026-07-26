@extends('layouts.app')
@section('title', 'Register | TechSphere')

@section('content')
<div class="container">
    <div class="auth-card">
        <h1>Create Account</h1>
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="form-group"><label>Name</label><input name="name" value="{{ old('name') }}" required>@error('name')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label>Email</label><input name="email" type="email" value="{{ old('email') }}" required>@error('email')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label>Password</label><input name="password" type="password" required>@error('password')<div class="error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label>Confirm Password</label><input name="password_confirmation" type="password" required></div>
            <button class="btn btn-primary auth-submit" type="submit">Register</button>
        </form>
    </div>
</div>
@endsection
