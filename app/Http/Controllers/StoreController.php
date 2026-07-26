<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\Phone;
use App\Models\Setting;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home()
    {
        return view('store.home', [
            'featuredPhones' => Phone::with(['brand', 'activeOffers', 'variants'])->where('is_featured', true)->take(4)->get(),
            'offers' => Offer::with(['phones.brand', 'accessories'])->whereDate('ends_at', '>=', now())->latest()->take(2)->get(),
            'brands' => Brand::withCount('phones')->get(),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function phones(Request $request)
    {
        $phones = Phone::with(['brand', 'activeOffers', 'variants'])
            ->when($request->search, fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', "%{$search}%"))))
            ->when($request->brand, fn ($query, $brand) => $query->where('brand_id', $brand))
            ->when($request->ram, fn ($query, $ram) => $query->where('ram', $ram))
            ->when($request->storage, fn ($query, $storage) => $query->where('storage', $storage))
            ->when($request->max_price, fn ($query, $price) => $query->where('price', '<=', (int) $price))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('store.phones', [
            'phones' => $phones,
            'brands' => Brand::orderBy('name')->get(),
            'ramOptions' => Phone::query()->distinct()->orderBy('ram')->pluck('ram'),
            'storageOptions' => Phone::query()->distinct()->orderBy('storage')->pluck('storage'),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function phone(Phone $phone)
    {
        $phone->load(['brand', 'activeOffers', 'variants']);

        return view('store.phone', [
            'phone' => $phone,
            'related' => Phone::with(['brand', 'activeOffers', 'variants'])->where('brand_id', $phone->brand_id)->whereKeyNot($phone->id)->take(3)->get(),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function accessories(Request $request)
    {
        return view('store.accessories', [
            'accessories' => Accessory::query()
                ->with('activeOffers')
                ->when($request->category, fn ($query, $category) => $query->where('category', $category))
                ->latest()
                ->get(),
            'categories' => Accessory::query()->distinct()->orderBy('category')->pluck('category'),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function compare(Request $request)
    {
        $raw = $request->input('phones', []);
        $ids = collect(is_array($raw) ? $raw : explode(',', (string) $raw))
            ->filter()
            ->take(3)
            ->map(fn ($id) => (int) $id)
            ->values();

        return view('store.compare', [
            'phones' => Phone::with(['brand', 'activeOffers'])->whereIn('id', $ids)->get()->sortBy(fn ($phone) => $ids->search($phone->id)),
            'allPhones' => Phone::with('brand')->orderBy('name')->get(),
            'selectedIds' => $ids,
        ]);
    }

    public function offers()
    {
        return view('store.offers', [
            'offers' => Offer::with(['phones.brand', 'accessories'])->whereDate('ends_at', '>=', now())->latest()->get(),
        ]);
    }
}
