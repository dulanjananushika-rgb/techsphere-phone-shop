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
            'whatsapp' => Setting::getValue('whatsapp_number', '94771234567'),
            'email' => Setting::getValue('shop_email', 'hello@techsphere.test'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'shop_email' => ['required', 'email', 'max:180'],
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
