<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::where('is_active', true);

        if ($request->has('query')) {
            $query->where('name', 'like', '%' . $request->get('query') . '%');
        }

        $tags = $query->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }
}
