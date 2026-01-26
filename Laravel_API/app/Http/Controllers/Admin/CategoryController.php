<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent')->latest();

        if ($request->has('type') && in_array($request->type, ['local_service', 'freelancer'])) {
            $query->where('type', $request->type);
        }

        $categories = $query->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:local_service,freelancer',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        if (!empty($data['parent_id'])) {
            $parent = Category::find($data['parent_id']);
            if ($parent) {
                $data['type'] = $parent->type ?? $data['type'];
            }
        }
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($path);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:local_service,freelancer',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        if (!empty($data['parent_id'])) {
            $parent = Category::find($data['parent_id']);
            if ($parent) {
                $data['type'] = $parent->type ?? $data['type'];
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($path);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
