<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::where('is_active', true)->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $languages
        ]);
    }
}
