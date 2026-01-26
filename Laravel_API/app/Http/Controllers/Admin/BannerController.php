<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image' => Storage::url($path),
            'title' => $request->title,
            'link' => $request->link,
            'status' => $request->status ?? true,
            'order' => Banner::count() + 1,
        ]);

        return back()->with('success', 'Banner added successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return back()->with('success', 'Banner deleted successfully.');
    }
}
