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
            'featuredPhones' => Phone::active()->with(['brand', 'activeOffers', 'variants'])->where('is_featured', true)->take(4)->get(),
            'offers' => Offer::active()->with(['phones' => fn ($query) => $query->active()->with('brand'), 'accessories' => fn ($query) => $query->active()])->latest()->take(2)->get(),
            'brands' => Brand::withCount(['phones' => fn ($query) => $query->active()])->get(),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function phones(Request $request)
    {
        $phones = Phone::active()->with(['brand', 'activeOffers', 'variants'])
            ->when($request->search, fn ($query, $search) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', "%{$search}%"))))
            ->when($request->brand, fn ($query, $brand) => $query->where('brand_id', $brand))
            ->when($request->ram, fn ($query, $ram) => $query->where('ram', $ram))
            ->when($request->storage, fn ($query, $storage) => $query->where(fn ($q) => $q
                ->where('storage', $storage)
                ->orWhereHas('variants', fn ($variants) => $variants->where('is_active', true)->where('storage', $storage))))
            ->when($request->max_price, fn ($query, $price) => $query->where(fn ($q) => $q
                ->where('price', '<=', (int) $price)
                ->orWhereHas('variants', fn ($variants) => $variants->where('is_active', true)->where('price', '<=', (int) $price))))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('store.phones', [
            'phones' => $phones,
            'brands' => Brand::orderBy('name')->get(),
            'ramOptions' => Phone::active()->distinct()->orderBy('ram')->pluck('ram'),
            'storageOptions' => Phone::active()->distinct()->orderBy('storage')->pluck('storage'),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function phone(Phone $phone)
    {
        abort_unless($phone->is_active, 404);
        $phone->load(['brand', 'activeOffers', 'variants']);

        return view('store.phone', [
            'phone' => $phone,
            'related' => Phone::active()->with(['brand', 'activeOffers', 'variants'])->where('brand_id', $phone->brand_id)->whereKeyNot($phone->id)->take(3)->get(),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function accessories(Request $request)
    {
        return view('store.accessories', [
            'accessories' => Accessory::active()
                ->with(['activeOffers', 'variants'])
                ->when($request->category, fn ($query, $category) => $query->where('category', $category))
                ->latest()
                ->get(),
            'categories' => Accessory::active()->distinct()->orderBy('category')->pluck('category'),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function compare(Request $request)
    {
        $raw = $request->input('phones', []);

        if (is_array($raw)) {
            $request->validate([
                'phones' => ['array', 'max:3'],
                'phones.*' => ['integer', 'distinct', 'exists:phones,id'],
            ], [
                'phones.max' => 'You can compare a maximum of three phones.',
            ]);
        }

        $ids = collect(is_array($raw) ? $raw : explode(',', (string) $raw))
            ->filter()
            ->take(3)
            ->map(fn ($id) => (int) $id)
            ->values();

        return view('store.compare', [
            'phones' => Phone::active()->with(['brand', 'activeOffers'])->whereIn('id', $ids)->get()->sortBy(fn ($phone) => $ids->search($phone->id)),
            'allPhones' => Phone::active()->with(['brand', 'activeOffers'])->orderBy('name')->get(),
            'selectedIds' => $ids,
        ]);
    }

    public function offers()
    {
        return view('store.offers', [
            'offers' => Offer::active()->with(['phones' => fn ($query) => $query->active()->with('brand'), 'accessories' => fn ($query) => $query->active()])->latest()->get(),
        ]);
    }
}
