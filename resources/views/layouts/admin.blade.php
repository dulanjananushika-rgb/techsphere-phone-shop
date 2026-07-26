<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | TechSphere</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <a class="brand" style="color:white;margin-bottom:20px" href="{{ route('admin.dashboard') }}"><span class="brand-mark">TS</span> Admin</a>
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
            <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>Orders</a>
            <a href="{{ route('admin.notifications.index') }}" @class(['active' => request()->routeIs('admin.notifications.*')])>Notifications</a>
            <a href="{{ route('admin.phones.index') }}" @class(['active' => request()->routeIs('admin.phones.*')])>Phones</a>
            <a href="{{ route('admin.variants.index') }}" @class(['active' => request()->routeIs('admin.variants.*')])>Variants</a>
            <a href="{{ route('admin.brands.index') }}" @class(['active' => request()->routeIs('admin.brands.*')])>Brands</a>
            <a href="{{ route('admin.accessories.index') }}" @class(['active' => request()->routeIs('admin.accessories.*')])>Accessories</a>
            <a href="{{ route('admin.offers.index') }}" @class(['active' => request()->routeIs('admin.offers.*')])>Offers</a>
            <a href="{{ route('admin.settings.edit') }}" @class(['active' => request()->routeIs('admin.settings.*')])>Settings</a>
            <a href="{{ route('home') }}" style="margin-top:20px">View Store</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">@csrf<button class="btn btn-danger" style="width:100%">Logout</button></form>
        </aside>
        <main class="admin-main">
            @if(session('status'))<div class="notice">{{ session('status') }}</div>@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
