<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }
}
