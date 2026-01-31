<?php

namespace App\Http\Controllers\Admin\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FreelancerCategoryController extends Controller
{
    public function index(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parent = null;

        $query = Category::with('parent')
            ->where('type', 'freelancer');

        if ($parentId) {
            $query->where('parent_id', $parentId);
            $parent = Category::find($parentId);
        } else {
            $query->whereNull('parent_id');
        }

        $categories = $query->latest()->paginate(10);
            
        return view('admin.freelancer_categories.index', compact('categories', 'parent'));
    }

    public function create(Request $request)
    {
        $parentId = $request->query('parent_id');
        $parents = Category::where('type', 'freelancer')->whereNull('parent_id')->get();
        return view('admin.freelancer_categories.create', compact('parents', 'parentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $request->all();
        $data['type'] = 'freelancer';
        $data['slug'] = Str::slug($request->name);
        $data['is_shown_in_footer'] = $request->has('is_shown_in_footer') ? 1 : 0;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($path);
        }

        Category::create($data);

        $redirectRoute = route('admin.freelancer-categories.index');
        if (!empty($data['parent_id'])) {
            $redirectRoute = route('admin.freelancer-categories.index', ['parent_id' => $data['parent_id']]);
        }

        return redirect($redirectRoute)
            ->with('success', 'Freelancer category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parents = Category::where('type', 'freelancer')
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();
            
        return view('admin.freelancer_categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_shown_in_footer'] = $request->has('is_shown_in_footer') ? 1 : 0;
        
        if ($request->hasFile('image')) {
            if ($category->image) {
                $oldPath = str_replace('/storage/', '', $category->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = Storage::url($path);
        }

        $category->update($data);

        $redirectRoute = route('admin.freelancer-categories.index');
        if (!empty($category->parent_id)) {
            $redirectRoute = route('admin.freelancer-categories.index', ['parent_id' => $category->parent_id]);
        }

        return redirect($redirectRoute)
            ->with('success', 'Freelancer category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $parentId = $category->parent_id;
        
        if ($category->image) {
            $oldPath = str_replace('/storage/', '', $category->image);
            Storage::disk('public')->delete($oldPath);
        }
        
        $category->delete();

        $redirectRoute = route('admin.freelancer-categories.index');
        if ($parentId) {
            $redirectRoute = route('admin.freelancer-categories.index', ['parent_id' => $parentId]);
        }

        return redirect($redirectRoute)
            ->with('success', 'Freelancer category deleted successfully.');
    }
}
