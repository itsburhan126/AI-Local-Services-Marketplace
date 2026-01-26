<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRule;
use Illuminate\Http\Request;

class ServiceRuleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:local_service,freelancer',
        ]);

        $query = ServiceRule::where('is_active', true)->orderBy('order', 'asc');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $rules = $query->get();

        return response()->json([
            'status' => true,
            'data' => $rules,
        ]);
    }
}
