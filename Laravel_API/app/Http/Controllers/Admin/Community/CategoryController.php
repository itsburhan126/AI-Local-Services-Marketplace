<?php

namespace App\Http\Controllers\Admin\Community;

use App\Http\Controllers\Controller;
use App\Models\CommunityCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = CommunityCategory::orderBy('order')->paginate(10);
        return view('admin.community.categories.index', compact('categories'));
    }
}
