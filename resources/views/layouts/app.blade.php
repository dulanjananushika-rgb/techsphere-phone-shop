<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Shop genuine phones and accessories with clear pricing, local support, pickup, and islandwide delivery.')">
    <meta name="theme-color" content="#101828">
    <title>@yield('title', $shopSettings['store_name'])</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <span>{{ $shopSettings['opening_hours'] }} | {{ $shopSettings['shop_address'] }}</span>
            <span>Islandwide delivery and local warranty support</span>
            <a class="topbar-contact" href="tel:{{ preg_replace('/\s+/', '', $shopSettings['shop_phone']) }}">{{ $shopSettings['shop_phone'] }}</a>
        </div>
    </div>

    <nav class="nav" aria-label="Main navigation">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">TS</span>
                <span>{{ $shopSettings['store_name'] }}<small>Phones and accessories</small></span>
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-label="Open navigation">
                <span class="menu-lines" aria-hidden="true"></span>
            </button>

            <div class="nav-menu">
                <div class="nav-links">
                    <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
                    <a href="{{ route('phones.index') }}" @class(['active' => request()->routeIs('phones.*')])>Phones</a>
                    <a href="{{ route('accessories.index') }}" @class(['active' => request()->routeIs('accessories.*')])>Accessories</a>
                    <a href="{{ route('offers.index') }}" @class(['active' => request()->routeIs('offers.*')])>Deals</a>
                    <a href="{{ route('compare') }}" @class(['active' => request()->routeIs('compare')])>Compare</a>
                    @auth
                        <a href="{{ route('wishlist.index') }}" @class(['active' => request()->routeIs('wishlist.*')])>Wishlist</a>
                        <a href="{{ route('orders.index') }}" @class(['active' => request()->routeIs('orders.index')])>My Orders</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @endif
                    @endauth
                </div>

                <div class="actions">
                    <a class="btn btn-green btn-small" target="_blank" rel="noopener" href="https://wa.me/{{ $shopSettings['whatsapp_number'] }}">WhatsApp</a>
                    @guest
                        <a class="btn btn-small" href="{{ route('login') }}">Log in</a>
                        <a class="btn btn-primary btn-small" href="{{ route('register') }}">Create account</a>
                    @else
                        <span class="nav-user" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-small" type="submit">Log out</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @if(session('status'))
        <div class="container" style="padding-top:18px"><div class="notice">{{ session('status') }}</div></div>
    @endif

    @if($errors->any())
        <div class="container" style="padding-top:18px">
            <div class="error-summary">{{ $errors->first() }}</div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="brand" href="{{ route('home') }}">
                        <span class="brand-mark">TS</span>
                        <span>{{ $shopSettings['store_name'] }}<small>Phones and accessories</small></span>
                    </a>
                    <p>Clear prices, verified stock, and personal support before and after your purchase.</p>
                </div>
                <div>
                    <h3>Shop</h3>
                    <div class="footer-links">
                        <a href="{{ route('phones.index') }}">Phones</a>
                        <a href="{{ route('accessories.index') }}">Accessories</a>
                        <a href="{{ route('offers.index') }}">Current deals</a>
                        <a href="{{ route('compare') }}">Compare phones</a>
                    </div>
                </div>
                <div>
                    <h3>Contact</h3>
                    <p>{{ $shopSettings['shop_address'] }}</p>
                    <p><a href="tel:{{ preg_replace('/\s+/', '', $shopSettings['shop_phone']) }}">{{ $shopSettings['shop_phone'] }}</a></p>
                    <p><a href="mailto:{{ $shopSettings['shop_email'] }}">{{ $shopSettings['shop_email'] }}</a></p>
                    <p>{{ $shopSettings['opening_hours'] }}</p>
                </div>
            </div>
            <div class="footer-bottom">&copy; {{ now()->year }} {{ $shopSettings['store_name'] }}. All rights reserved.</div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
