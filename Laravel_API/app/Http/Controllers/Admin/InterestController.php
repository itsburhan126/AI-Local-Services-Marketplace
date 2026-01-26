<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InterestController extends Controller
{
    public function index()
    {
        $interests = Interest::orderBy('order', 'asc')->paginate(15);
        $categories = Category::all();
        return view('admin.interests.index', compact('interests', 'categories'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:interests,id',
        ]);

        foreach ($request->ids as $index => $id) {
            Interest::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $path = $request->file('icon')->store('interests', 'public');

        Interest::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => '/storage/' . $path,
            'category_id' => $request->category_id,
            'order' => Interest::max('order') + 1,
        ]);

        return back()->with('success', 'Interest added successfully.');
    }

    public function update(Request $request, Interest $interest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('interests', 'public');
            $data['icon'] = '/storage/' . $path;
        }

        $interest->update($data);

        return back()->with('success', 'Interest updated successfully.');
    }

    public function destroy(Interest $interest)
    {
        $interest->delete();
        return back()->with('success', 'Interest deleted successfully.');
    }
}
