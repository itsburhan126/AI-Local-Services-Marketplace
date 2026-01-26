<?php

namespace App\Http\Controllers\Admin\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class FreelancerCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->where('type', 'freelancer')
            ->latest()
            ->paginate(10);
            
        return view('admin.freelancer_categories.index', compact('categories'));
    }
}
