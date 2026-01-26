<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReferralController extends Controller
{
    public function index()
    {
        $settings = Setting::where('group', 'referral')->pluck('value', 'key')->toArray();
        return view('admin.referral.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'referral_title' => 'required|string|max:255',
            'referral_description' => 'required|string',
            'referral_link' => 'nullable|string',
            'referral_image' => 'nullable|image|max:2048',
            'referral_enabled' => 'nullable|in:1,0',
        ]);

        // Handle Image Upload
        if ($request->hasFile('referral_image')) {
            $path = $request->file('referral_image')->store('referral', 'public');
            Setting::updateOrCreate(
                ['key' => 'referral_image'],
                ['value' => '/storage/' . $path, 'group' => 'referral']
            );
        }

        // Save other settings
        $fields = ['referral_title', 'referral_description', 'referral_link'];
        foreach ($fields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field), 'group' => 'referral']
            );
        }
        
        // Handle boolean toggle
        Setting::updateOrCreate(
            ['key' => 'referral_enabled'],
            ['value' => $request->has('referral_enabled') ? '1' : '0', 'group' => 'referral']
        );

        return back()->with('success', 'Referral settings updated successfully.');
    }
}
