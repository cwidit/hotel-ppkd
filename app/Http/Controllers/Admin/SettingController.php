<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'hotel_address' => 'nullable|string',
            'hotel_phone' => 'nullable|string',
            'hotel_email' => 'nullable|email',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'service_charge_percentage' => 'required|numeric|min:0|max:100',
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan hotel berhasil diperbarui.');
    }
}
