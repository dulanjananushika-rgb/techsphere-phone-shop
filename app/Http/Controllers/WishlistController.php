<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Setting;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return view('store.wishlist', [
            'items' => Wishlist::with('phone.brand', 'phone.activeOffers')
                ->where('user_id', $request->user()->id)
                ->whereHas('phone', fn ($query) => $query->active())
                ->latest()
                ->get(),
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
        ]);
    }

    public function toggle(Request $request, Phone $phone)
    {
        abort_unless($phone->is_active, 404);

        $wishlist = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->where('phone_id', $phone->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return back()->with('status', 'Removed from wishlist.');
        }

        Wishlist::create([
            'user_id' => $request->user()->id,
            'phone_id' => $phone->id,
        ]);

        return back()->with('status', 'Added to wishlist.');
    }
}
