<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::latest()->get();
        return view('admin.subscription_plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription_plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string|distinct',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        
        // Remove empty features
        if (isset($data['features'])) {
            $data['features'] = array_filter($data['features'], fn($value) => !is_null($value) && $value !== '');
        }

        SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription_plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string|distinct',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        // Remove empty features
        if (isset($data['features'])) {
            $data['features'] = array_filter($data['features'], fn($value) => !is_null($value) && $value !== '');
        }

        $subscriptionPlan->update($data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();
        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }
}
