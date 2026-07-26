<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TechSphere Phone Shop')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <span>Colombo showroom open daily 9.00 AM - 8.00 PM</span>
            <span>Islandwide delivery</span>
            <span>Warranty support available</span>
        </div>
    </div>
    <nav class="nav">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark">T</span><span>TechSphere <small>Mobile</small></span></a>
            <div class="nav-links">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
                <a href="{{ route('phones.index') }}" @class(['active' => request()->routeIs('phones.*')])>Phones</a>
                <a href="{{ route('accessories.index') }}" @class(['active' => request()->routeIs('accessories.*')])>Accessories</a>
                <a href="{{ route('offers.index') }}" @class(['active' => request()->routeIs('offers.*')])>Deals</a>
                <a href="{{ route('compare') }}" @class(['active' => request()->routeIs('compare')])>Compare</a>
                @auth
                    <a href="{{ route('wishlist.index') }}" @class(['active' => request()->routeIs('wishlist.*')])>Wishlist</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                @endauth
            </div>
            <div class="actions">
                <a class="btn btn-green btn-small" target="_blank" href="https://wa.me/94771234567">WhatsApp</a>
                @guest
                    <a class="btn btn-small" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary btn-small" href="{{ route('register') }}">Register</a>
                @else
                    <span class="muted">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-small">Logout</button></form>
                @endguest
            </div>
        </div>
    </nav>

    @if(session('status'))
        <div class="container section" style="padding-bottom:0"><div class="notice">{{ session('status') }}</div></div>
    @endif

    @yield('content')

    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <strong>TechSphere</strong>
                <p class="muted">A modern phone shop for new devices, daily accessories, and fast WhatsApp ordering.</p>
            </div>
            <div class="muted">Colombo, Sri Lanka<br>hello@techsphere.test</div>
        </div>
    </footer>
</body>
</html>
