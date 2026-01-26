<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:service,category,url',
            'target_id' => 'nullable|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promotions', 'public');
            $data['image'] = Storage::url($path);
        }

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:service,category,url',
            'target_id' => 'nullable|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promotions', 'public');
            $data['image'] = Storage::url($path);
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
}
