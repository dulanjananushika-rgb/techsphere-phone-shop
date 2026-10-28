<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#07090e">
    <title>@yield('title', 'Admin Dashboard') | {{ $shopSettings['store_name'] }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    @stack('head')
</head>
<body class="admin-page">
    <div class="admin-shell">
        <aside class="sidebar" id="admin-sidebar">
            <a class="brand" href="{{ route('admin.dashboard') }}" style="margin-bottom:12px">
                <span class="brand-mark">TS</span>
                <span>Command Hub<small>{{ $shopSettings['store_name'] }}</small></span>
            </a>

            <div class="sidebar-section">Store Operations</div>
            <nav class="sidebar-nav" aria-label="Admin navigation">
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>
                    <span>📊 Dashboard Analytics</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>
                    <span>📦 Orders & Reservations</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" @class(['active' => request()->routeIs('admin.notifications.*')])>
                    <span>🔔 Communication Audit</span>
                </a>
            </nav>

            <div class="sidebar-section">Catalog Management</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.phones.index') }}" @class(['active' => request()->routeIs('admin.phones.*')])>
                    <span>📱 Smartphone Inventory</span>
                </a>
                <a href="{{ route('admin.variants.index') }}" @class(['active' => request()->routeIs('admin.variants.*')])>
                    <span>🎨 Variants, Colors & SKUs</span>
                </a>
                <a href="{{ route('admin.brands.index') }}" @class(['active' => request()->routeIs('admin.brands.*')])>
                    <span>🏷️ Brand Manufacturers</span>
                </a>
                <a href="{{ route('admin.accessories.index') }}" @class(['active' => request()->routeIs('admin.accessories.*')])>
                    <span>🎧 Accessories Catalog</span>
                </a>
                <a href="{{ route('admin.offers.index') }}" @class(['active' => request()->routeIs('admin.offers.*')])>
                    <span>⚡ Promotional Offers</span>
                </a>
            </nav>

            <div class="sidebar-section">Configuration</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.settings.edit') }}" @class(['active' => request()->routeIs('admin.settings.*')])>
                    <span>⚙️ Showroom Settings</span>
                </a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">
                    <span>🌐 Live Storefront &rarr;</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-block" type="submit">Log Out of Console</button>
                </form>
            </div>
        </aside>

        <button class="sidebar-backdrop" type="button" data-sidebar-backdrop aria-label="Close navigation"></button>

        <div class="admin-content">
            <header class="admin-top">
                <div style="display:flex;align-items:center;gap:16px">
                    <button class="admin-menu-toggle" type="button" data-admin-toggle aria-expanded="false" aria-controls="admin-sidebar" aria-label="Open admin navigation" style="background:transparent;border:none;cursor:pointer;color:#ffffff;padding:8px">
                        <span class="menu-lines" aria-hidden="true"></span>
                    </button>
                    <strong style="font-size:1.15rem;color:#ffffff">@yield('title', 'Admin Dashboard')</strong>
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981;box-shadow:0 0 10px #10b981"></span>
                    <span class="nav-user">Admin: {{ auth()->user()->name }}</span>
                </div>
            </header>

            <main class="admin-main">
                @if(session('status'))
                    <div class="notice">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="error-summary">{{ $errors->first() }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script src="/js/app.js" defer></script>
    @stack('scripts')
</body>
</html>
