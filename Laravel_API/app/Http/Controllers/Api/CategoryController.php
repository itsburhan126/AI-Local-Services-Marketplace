<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $parentId = $request->query('parent_id');

        $query = Category::query()
            ->where('is_active', true)
            ->orderBy('order');

        if ($type) {
            $query->where('type', $type);
        }

        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }

        return response()->json([
            'status' => true,
            'data' => $query->get(),
        ]);
    }
}

