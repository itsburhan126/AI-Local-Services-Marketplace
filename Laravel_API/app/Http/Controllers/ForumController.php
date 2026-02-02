<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityCategory;
use App\Models\ForumPost;
use App\Models\ForumReply;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumPost::with(['user', 'category'])
            ->withCount('replies');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(15);
        $categories = CommunityCategory::where('type', '!=', 'event')->where('is_active', true)->get();

        return view('pages.community.forum.index', compact('posts', 'categories'));
    }

    public function category($slug)
    {
        $category = CommunityCategory::where('slug', $slug)->firstOrFail();
        $posts = ForumPost::where('community_category_id', $category->id)
            ->with(['user', 'category'])
            ->withCount('replies')
            ->latest()
            ->paginate(15);
            
        $categories = CommunityCategory::where('type', '!=', 'event')->where('is_active', true)->get();

        return view('pages.community.forum.category', compact('category', 'posts', 'categories'));
    }

    public function show($slug)
    {
        $post = ForumPost::where('slug', $slug)
            ->with(['user', 'category', 'replies.user'])
            ->firstOrFail();
            
        $post->increment('view_count');

        $recentPosts = ForumPost::where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();
        
        return view('pages.community.forum.show', compact('post', 'recentPosts'));
    }

    public function create()
    {
        $categories = CommunityCategory::where('type', '!=', 'event')->where('is_active', true)->get();
        return view('pages.community.forum.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'community_category_id' => 'required|exists:community_categories,id',
            'content' => 'required',
        ]);

        $post = ForumPost::create([
            'user_id' => Auth::id(),
            'community_category_id' => $request->community_category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
        ]);

        return redirect()->route('community.forum.show', $post->slug)->with('success', 'Topic created successfully!');
    }

    public function reply(Request $request, $slug)
    {
        $post = ForumPost::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'content' => 'required',
        ]);

        ForumReply::create([
            'forum_post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Reply posted successfully!');
    }
}
