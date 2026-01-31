<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuccessStoryController extends Controller
{
    public function index()
    {
        $stories = SuccessStory::latest()->paginate(10);
        return view('admin.success_stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.success_stories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quote' => 'required|string',
            'story_content' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Hero image
            'avatar' => 'nullable|image|max:1024', // Avatar
            'service_category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('success_stories', 'public');
        }

        if ($request->hasFile('avatar')) {
            $validated['avatar_path'] = $request->file('avatar')->store('success_stories/avatars', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        SuccessStory::create($validated);

        return redirect()->route('admin.success-stories.index')->with('success', 'Success story created successfully.');
    }

    public function edit(SuccessStory $successStory)
    {
        return view('admin.success_stories.edit', compact('successStory'));
    }

    public function update(Request $request, SuccessStory $successStory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quote' => 'required|string',
            'story_content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'avatar' => 'nullable|image|max:1024',
            'service_category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($successStory->image_path) {
                Storage::disk('public')->delete($successStory->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('success_stories', 'public');
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($successStory->avatar_path) {
                Storage::disk('public')->delete($successStory->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('success_stories/avatars', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $successStory->update($validated);

        return redirect()->route('admin.success-stories.index')->with('success', 'Success story updated successfully.');
    }

    public function destroy(SuccessStory $successStory)
    {
        if ($successStory->image_path) {
            Storage::disk('public')->delete($successStory->image_path);
        }
        if ($successStory->avatar_path) {
            Storage::disk('public')->delete($successStory->avatar_path);
        }
        
        $successStory->delete();

        return redirect()->route('admin.success-stories.index')->with('success', 'Success story deleted successfully.');
    }
}
