<?php

namespace App\Http\Controllers\Admin\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerInterest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FreelancerInterestController extends Controller
{
    public function index()
    {
        $interests = FreelancerInterest::orderBy('order', 'asc')->paginate(15);
        $categories = Category::where('type', 'freelancer')->get(); // Filter for freelancer categories
        return view('admin.freelancer_interests.index', compact('interests', 'categories'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:freelancer_interests,id',
        ]);

        foreach ($request->ids as $index => $id) {
            FreelancerInterest::where('id', $id)->update(['order' => $index + 1]);
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

        $path = $request->file('icon')->store('freelancer_interests', 'public');

        FreelancerInterest::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => '/storage/' . $path,
            'category_id' => $request->category_id,
            'order' => FreelancerInterest::max('order') + 1,
        ]);

        return back()->with('success', 'Freelancer Interest added successfully.');
    }

    public function update(Request $request, FreelancerInterest $freelancerInterest)
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
            $path = $request->file('icon')->store('freelancer_interests', 'public');
            $data['icon'] = '/storage/' . $path;
        }

        $freelancerInterest->update($data);

        return back()->with('success', 'Freelancer Interest updated successfully.');
    }

    public function destroy(FreelancerInterest $freelancerInterest)
    {
        $freelancerInterest->delete();
        return back()->with('success', 'Freelancer Interest deleted successfully.');
    }
}
