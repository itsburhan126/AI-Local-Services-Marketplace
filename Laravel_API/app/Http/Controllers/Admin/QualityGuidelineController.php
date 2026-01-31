<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QualityGuideline;
use Illuminate\Http\Request;

class QualityGuidelineController extends Controller
{
    public function index()
    {
        $guidelines = QualityGuideline::orderBy('sort_order')->paginate(10);
        return view('admin.quality_guidelines.index', compact('guidelines'));
    }

    public function create()
    {
        return view('admin.quality_guidelines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon_class' => 'required|string|max:255',
            'color_class' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        QualityGuideline::create($validated);

        return redirect()->route('admin.quality-guidelines.index')->with('success', 'Quality guideline created successfully.');
    }

    public function edit(QualityGuideline $qualityGuideline)
    {
        return view('admin.quality_guidelines.edit', compact('qualityGuideline'));
    }

    public function update(Request $request, QualityGuideline $qualityGuideline)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon_class' => 'required|string|max:255',
            'color_class' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $qualityGuideline->update($validated);

        return redirect()->route('admin.quality-guidelines.index')->with('success', 'Quality guideline updated successfully.');
    }

    public function destroy(QualityGuideline $qualityGuideline)
    {
        $qualityGuideline->delete();

        return redirect()->route('admin.quality-guidelines.index')->with('success', 'Quality guideline deleted successfully.');
    }
}
