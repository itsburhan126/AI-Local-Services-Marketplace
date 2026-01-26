<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::where('is_active', true)->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $skills
        ]);
    }
}
