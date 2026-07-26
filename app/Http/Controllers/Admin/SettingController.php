<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => Setting::storeProfile(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'shop_email' => ['required', 'email', 'max:180'],
            'shop_phone' => ['required', 'string', 'max:30'],
            'shop_address' => ['required', 'string', 'max:300'],
            'opening_hours' => ['required', 'string', 'max:120'],
            'delivery_fee' => ['required', 'integer', 'min:0', 'max:100000'],
            'reservation_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'bank_name' => ['nullable', 'string', 'max:120'],
            'bank_account_name' => ['nullable', 'string', 'max:120'],
            'bank_account_number' => ['nullable', 'string', 'max:80'],
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
