<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $query = Guide::where('is_active', true);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $guides = $query->latest()->paginate(9);
        $categories = Guide::where('is_active', true)->select('category')->distinct()->pluck('category');

        return view('pages.guides', compact('guides', 'categories'));
    }

    public function show($slug)
    {
        $guide = Guide::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('pages.guide-show', compact('guide'));
    }
}
