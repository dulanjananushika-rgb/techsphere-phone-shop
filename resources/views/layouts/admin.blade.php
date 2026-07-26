<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111c2b">
    <title>@yield('title', 'Admin') | {{ $shopSettings['store_name'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body class="admin-page">
    <div class="admin-shell">
        <aside class="sidebar" id="admin-sidebar">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-mark">TS</span>
                <span>Shop Admin<small>{{ $shopSettings['store_name'] }}</small></span>
            </a>

            <div class="sidebar-section">Operations</div>
            <nav class="sidebar-nav" aria-label="Admin navigation">
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
                <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>Orders</a>
                <a href="{{ route('admin.notifications.index') }}" @class(['active' => request()->routeIs('admin.notifications.*')])>Notifications</a>
            </nav>

            <div class="sidebar-section">Catalog</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.phones.index') }}" @class(['active' => request()->routeIs('admin.phones.*')])>Phones</a>
                <a href="{{ route('admin.variants.index') }}" @class(['active' => request()->routeIs('admin.variants.*')])>Variants and stock</a>
                <a href="{{ route('admin.brands.index') }}" @class(['active' => request()->routeIs('admin.brands.*')])>Brands</a>
                <a href="{{ route('admin.accessories.index') }}" @class(['active' => request()->routeIs('admin.accessories.*')])>Accessories</a>
                <a href="{{ route('admin.offers.index') }}" @class(['active' => request()->routeIs('admin.offers.*')])>Special offers</a>
            </nav>

            <div class="sidebar-section">Store</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.settings.edit') }}" @class(['active' => request()->routeIs('admin.settings.*')])>Settings</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener">View storefront</a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-block" type="submit">Log out</button>
                </form>
            </div>
        </aside>

        <button class="sidebar-backdrop" type="button" data-sidebar-backdrop aria-label="Close navigation"></button>

        <div class="admin-content">
            <header class="admin-top">
                <button class="admin-menu-toggle" type="button" data-admin-toggle aria-expanded="false" aria-controls="admin-sidebar" aria-label="Open admin navigation">
                    <span class="menu-lines" aria-hidden="true"></span>
                </button>
                <strong>@yield('title', 'Dashboard')</strong>
                <span class="nav-user">{{ auth()->user()->name }}</span>
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

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
