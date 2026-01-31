<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::latest()->paginate(10);
        return view('admin.guides.index', compact('guides'));
    }

    public function create()
    {
        return view('admin.guides.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|string',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('guides', 'public');
            $data['image_path'] = $imagePath;
        }

        Guide::create($data);

        return redirect()->route('admin.guides.index')->with('success', 'Guide created successfully.');
    }

    public function edit(Guide $guide)
    {
        return view('admin.guides.edit', compact('guide'));
    }

    public function update(Request $request, Guide $guide)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|string',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($guide->image_path) {
                Storage::disk('public')->delete($guide->image_path);
            }
            $imagePath = $request->file('image')->store('guides', 'public');
            $data['image_path'] = $imagePath;
        }

        $guide->update($data);

        return redirect()->route('admin.guides.index')->with('success', 'Guide updated successfully.');
    }

    public function destroy(Guide $guide)
    {
        if ($guide->image_path) {
            Storage::disk('public')->delete($guide->image_path);
        }
        $guide->delete();
        return redirect()->route('admin.guides.index')->with('success', 'Guide deleted successfully.');
    }
}
