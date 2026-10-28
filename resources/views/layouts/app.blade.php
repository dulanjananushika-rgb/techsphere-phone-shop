<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Experience premier flagship smartphones and genuine accessories in Sri Lanka with authentic warranties and atomic stock reservations.')">
    <meta name="theme-color" content="#080a11">
    <title>@yield('title', $shopSettings['store_name'].' | Flagship Showroom')</title>
    
    <!-- Next-Gen Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/css/app.css">
    @stack('head')
</head>
<body>
    <!-- Top Announcement Bar -->
    <header class="topbar">
        <div class="container topbar-inner">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 10px #10b981"></span>
                <span>{{ $shopSettings['opening_hours'] }} &bull; {{ $shopSettings['shop_address'] }}</span>
            </div>
            <div>
                <span style="color:var(--titanium-gold);font-weight:800">⚡ 100% Genuine TRCSL Approved</span> &bull; 1-Year Official Warranty &bull; Islandwide Express
            </div>
            <a class="topbar-contact" href="tel:{{ preg_replace('/\s+/', '', $shopSettings['shop_phone']) }}">
                <span>Direct Hotline:</span> {{ $shopSettings['shop_phone'] }}
            </a>
        </div>
    </header>

    <!-- Frosted Glass Navbar -->
    <nav class="nav" aria-label="Main navigation">
        <div class="container nav-inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">TS</span>
                <span>{{ $shopSettings['store_name'] }}<small>Flagships & Authentic Gear</small></span>
            </a>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-label="Open navigation">
                <span class="menu-lines" aria-hidden="true"></span>
            </button>

            <div class="nav-menu">
                <div class="nav-links">
                    <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a>
                    <a href="{{ route('phones.index') }}" @class(['active' => request()->routeIs('phones.*')])>Phones</a>
                    <a href="{{ route('accessories.index') }}" @class(['active' => request()->routeIs('accessories.*')])>Accessories</a>
                    <a href="{{ route('offers.index') }}" @class(['active' => request()->routeIs('offers.*')])>Offers</a>
                    <a href="{{ route('compare') }}" @class(['active' => request()->routeIs('compare')])>Compare</a>
                    @auth
                        <a href="{{ route('wishlist.index') }}" @class(['active' => request()->routeIs('wishlist.*')])>Wishlist</a>
                        <a href="{{ route('orders.index') }}" @class(['active' => request()->routeIs('orders.index')])>My Orders</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" style="color:var(--titanium-gold);font-weight:700">Admin Console</a>
                        @endif
                    @endauth
                </div>

                <div class="actions">
                    <a class="btn btn-green btn-small" target="_blank" rel="noopener" href="https://wa.me/{{ $shopSettings['whatsapp_number'] }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I would like to inquire about phone availability.') }}">
                        <span style="font-size:1.1em">💬</span> WhatsApp
                    </a>
                    @guest
                        <a class="btn btn-small" href="{{ route('login') }}">Log in</a>
                        <a class="btn btn-primary btn-small" href="{{ route('register') }}">Create Account</a>
                    @else
                        <span class="nav-user" title="{{ auth()->user()->name }}">👤 {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-small" type="submit">Log out</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Flash Status Messages -->
    @if(session('status'))
        <div class="container" style="padding-top:24px">
            <div class="notice">{{ session('status') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="container" style="padding-top:24px">
            <div class="error-summary">{{ $errors->first() }}</div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Floating WhatsApp Widget -->
    <a class="floating-whatsapp print-hide" target="_blank" rel="noopener" href="https://wa.me/{{ $shopSettings['whatsapp_number'] }}?text={{ urlencode('Hi '.$shopSettings['store_name'].', I would like to ask about availability and pricing.') }}" aria-label="Chat on WhatsApp">
        <span style="font-size:1.35rem">💬</span>
        <span>Showroom Hotline</span>
    </a>

    <!-- Luxury Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="brand" href="{{ route('home') }}">
                        <span class="brand-mark">TS</span>
                        <span>{{ $shopSettings['store_name'] }}<small>Authentic Mobile Tech</small></span>
                    </a>
                    <p style="margin-top:14px;max-width:440px;line-height:1.75">
                        Sri Lanka's trusted destination for genuine flagship smartphones and authentic accessories. Transparent pricing, atomic instant stock reservation, and dedicated showroom support.
                    </p>
                    <div style="display:flex;gap:12px;margin-top:20px;flex-wrap:wrap">
                        <span style="padding:6px 12px;background:rgba(255,255,255,0.04);border-radius:8px;font-size:0.75rem;font-weight:700;color:var(--titanium-gold);border:1px solid rgba(226,177,112,0.3)">🛡️ 1-Year Local Warranty</span>
                        <span style="padding:6px 12px;background:rgba(255,255,255,0.04);border-radius:8px;font-size:0.75rem;font-weight:700;color:var(--emerald-mint);border:1px solid rgba(16,185,129,0.3)">⚡ Atomic Stock Lock</span>
                        <span style="padding:6px 12px;background:rgba(255,255,255,0.04);border-radius:8px;font-size:0.75rem;font-weight:700;color:var(--sapphire-light);border:1px solid rgba(96,165,250,0.3)">🚚 Islandwide Express</span>
                    </div>
                </div>
                <div>
                    <h3>Quick Navigation</h3>
                    <div class="footer-links">
                        <a href="{{ route('phones.index') }}">Smartphone Catalog</a>
                        <a href="{{ route('accessories.index') }}">Curated Accessories</a>
                        <a href="{{ route('offers.index') }}">Active Promotional Deals</a>
                        <a href="{{ route('compare') }}">Flagship Comparison Hub</a>
                        <a href="{{ route('orders.index') }}">Track Order Reservation</a>
                    </div>
                </div>
                <div>
                    <h3>Showroom & Support</h3>
                    <p style="color:var(--text-white);font-weight:700">{{ $shopSettings['shop_address'] }}</p>
                    <p><a href="tel:{{ preg_replace('/\s+/', '', $shopSettings['shop_phone']) }}" style="color:var(--sapphire-light);font-weight:700">{{ $shopSettings['shop_phone'] }}</a></p>
                    <p><a href="mailto:{{ $shopSettings['shop_email'] }}">{{ $shopSettings['shop_email'] }}</a></p>
                    <p style="color:var(--text-secondary)">{{ $shopSettings['opening_hours'] }}</p>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ now()->year }} {{ $shopSettings['store_name'] }}. Built with cutting-edge engineering. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="/js/app.js" defer></script>
    @stack('scripts')
</body>
</html>
