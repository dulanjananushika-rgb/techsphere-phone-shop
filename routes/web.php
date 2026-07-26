<?php

use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PhoneController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/phones', [StoreController::class, 'phones'])->name('phones.index');
Route::get('/phones/{phone:slug}', [StoreController::class, 'phone'])->name('phones.show');
Route::get('/accessories', [StoreController::class, 'accessories'])->name('accessories.index');
Route::get('/compare', [StoreController::class, 'compare'])->name('compare');
Route::get('/offers', [StoreController::class, 'offers'])->name('offers.index');
Route::get('/order/phone/{phone}', [OrderController::class, 'phone'])->name('orders.phone');
Route::get('/order/accessory/{accessory}', [OrderController::class, 'accessory'])->name('orders.accessory');
Route::post('/orders', [OrderController::class, 'store'])->middleware('throttle:orders')->name('orders.store');
Route::get('/orders/{order:access_token}/success', [OrderController::class, 'success'])->name('orders.success');
Route::get('/orders/{order:access_token}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{phone}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{notification}', [NotificationController::class, 'update'])->name('notifications.update');
    Route::post('notifications/{notification}/retry', [NotificationController::class, 'retry'])->name('notifications.retry');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::resource('phones', PhoneController::class)->except('show');
    Route::resource('variants', ProductVariantController::class)->except('show');
    Route::resource('brands', BrandController::class)->except('show');
    Route::resource('accessories', AccessoryController::class)->except('show');
    Route::resource('offers', OfferController::class)->except('show');
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
