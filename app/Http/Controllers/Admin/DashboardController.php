<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Brand;
use App\Models\NotificationLog;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Phone;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'phones' => Phone::count(),
                'brands' => Brand::count(),
                'accessories' => Accessory::count(),
                'offers' => Offer::count(),
                'orders' => Order::where('status', 'new')->count(),
                'users' => User::where('is_admin', false)->count(),
            ],
            'latestPhones' => Phone::with(['brand', 'variants'])->latest()->take(5)->get(),
            'latestOrders' => Order::latest()->take(5)->get(),
            'latestNotifications' => NotificationLog::latest()->take(5)->get(),
        ]);
    }
}
