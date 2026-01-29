<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutMethod;
use Illuminate\Http\Request;

class PayoutMethodController extends Controller
{
    public function index()
    {
        $methods = PayoutMethod::latest()->get();
        return view('admin.payout_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payout_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'fields' => 'nullable|array', // Allow optional fields
            'fields.*.name' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|string',
            'min_amount' => 'required|numeric|min:0',
            'processing_time_days' => 'required|integer|min:0',
        ]);

        $data = $request->except('logo');
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('payout_methods', 'public');
            $data['logo'] = $path;
        }

        $data['is_active'] = $request->has('is_active');

        PayoutMethod::create($data);

        return redirect()->route('admin.payout-methods.index')->with('success', 'Payout method created successfully.');
    }

    public function edit(PayoutMethod $payoutMethod)
    {
        return view('admin.payout_methods.edit', compact('payoutMethod'));
    }

    public function update(Request $request, PayoutMethod $payoutMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'fields' => 'nullable|array',
            'min_amount' => 'required|numeric|min:0',
            'processing_time_days' => 'required|integer|min:0',
        ]);

        $data = $request->except('logo');
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('payout_methods', 'public');
            $data['logo'] = $path;
        }

        $data['is_active'] = $request->has('is_active');

        $payoutMethod->update($data);

        return redirect()->route('admin.payout-methods.index')->with('success', 'Payout method updated successfully.');
    }

    public function destroy(PayoutMethod $payoutMethod)
    {
        $payoutMethod->delete();
        return redirect()->route('admin.payout-methods.index')->with('success', 'Payout method deleted successfully.');
    }
}
