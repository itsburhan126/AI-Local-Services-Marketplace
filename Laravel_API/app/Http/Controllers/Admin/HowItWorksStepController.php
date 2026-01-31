<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HowItWorksStep;
use Illuminate\Http\Request;

class HowItWorksStepController extends Controller
{
    public function index()
    {
        $steps = HowItWorksStep::orderBy('type')->orderBy('step_order')->get();
        return view('admin.how-it-works.index', compact('steps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:client,freelancer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'step_order' => 'required|integer',
        ]);

        HowItWorksStep::create($request->all());

        return back()->with('success', 'Step created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $step = HowItWorksStep::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'step_order' => 'required|integer',
        ]);

        $step->update($request->all());

        return back()->with('success', 'Step updated successfully.');
    }

    public function destroy(string $id)
    {
        HowItWorksStep::findOrFail($id)->delete();
        return back()->with('success', 'Step deleted successfully.');
    }
}
