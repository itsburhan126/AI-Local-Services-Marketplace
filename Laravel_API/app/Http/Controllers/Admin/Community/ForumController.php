<?php

namespace App\Http\Controllers\Admin\Community;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $posts = ForumPost::with(['user', 'category'])->latest()->paginate(10);
        return view('admin.community.forum.index', compact('posts'));
    }
}
