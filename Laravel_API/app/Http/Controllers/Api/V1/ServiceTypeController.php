<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $types = ServiceType::where('is_active', true)->get();
        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }
}
