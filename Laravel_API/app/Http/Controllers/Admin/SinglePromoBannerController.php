<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SinglePromoBannerController extends Controller
{
    public function edit()
    {
        $banner = Banner::where('type', 'promo_large')->firstOrNew([
            'type' => 'promo_large'
        ]);

        return view('Admin.banners.single_promo', compact('banner'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'link' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $banner = Banner::where('type', 'promo_large')->firstOrNew([
            'type' => 'promo_large'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists and not a placeholder
            if ($banner->image && !str_contains($banner->image, 'http')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
            }
            
            $path = $request->file('image')->store('banners', 'public');
            $banner->image = $path; // Store relative path, accessor or blade will handle full URL
        }

        $banner->title = $request->title;
        $banner->subtitle = $request->subtitle;
        $banner->button_text = $request->button_text;
        $banner->link = $request->link;
        $banner->status = $request->has('status'); // checkbox sends 'on' or nothing, usually we handle this carefully
        // If status is sent as '1' or '0' string from a hidden input + checkbox combo, that's better.
        // Let's assume standard Laravel boolean validation:
        $banner->status = $request->boolean('status');
        
        $banner->save();

        return back()->with('success', 'Single Promotional Banner updated successfully.');
    }
}
