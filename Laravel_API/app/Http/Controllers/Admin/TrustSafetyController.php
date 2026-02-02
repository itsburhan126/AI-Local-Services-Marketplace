<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrustSafetyItem;
use Illuminate\Http\Request;

class TrustSafetyController extends Controller
{
    public function index()
    {
        $items = TrustSafetyItem::orderBy('order')->paginate(10);
        return view('admin.trust_safety.index', compact('items'));
    }

    public function create()
    {
        return view('admin.trust_safety.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string', // Emoji or text
            'bg_color' => 'required|string|max:50',
            'text_color' => 'required|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        TrustSafetyItem::create($validated);

        return redirect()->route('admin.trust-safety.index')->with('success', 'Trust & Safety item created successfully.');
    }

    public function edit(TrustSafetyItem $trustSafety)
    {
        // Route binding might look for {trust_safety}, so we'll adjust the variable name or route param
        return view('admin.trust_safety.edit', compact('trustSafety'));
    }

    public function update(Request $request, TrustSafetyItem $trustSafety)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
            'bg_color' => 'required|string|max:50',
            'text_color' => 'required|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        $trustSafety->update($validated);

        return redirect()->route('admin.trust-safety.index')->with('success', 'Trust & Safety item updated successfully.');
    }

    public function destroy(TrustSafetyItem $trustSafety)
    {
        $trustSafety->delete();

        return redirect()->route('admin.trust-safety.index')->with('success', 'Trust & Safety item deleted successfully.');
    }
}
