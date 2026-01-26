<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProviderSubscription;
use Illuminate\Http\Request;

class ProviderSubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = ProviderSubscription::with(['user', 'plan'])
            ->latest()
            ->paginate(10);
        return view('admin.provider_subscriptions.index', compact('subscriptions'));
    }
}
