<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'required|string',
            'currency_code' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:5',
            'pusher_app_id' => 'nullable|string',
            'pusher_app_key' => 'nullable|string',
            'pusher_app_secret' => 'nullable|string',
            'pusher_app_cluster' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        $settings = $request->except(['_token', 'logo', 'favicon']);

        // Handle File Uploads
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', Storage::url($path));
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', Storage::url($path));
        }

        // Save other settings
        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
