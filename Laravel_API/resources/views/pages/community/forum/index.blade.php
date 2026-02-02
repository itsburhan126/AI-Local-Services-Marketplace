@extends('layouts.customer')

@section('title', 'Community Forum')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Community Forum</h1>
                <p class="text-gray-600 mt-1">Join the conversation and share your knowledge.</p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('community.forum.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search topics..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </form>
                <a href="{{ route('community.forum.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i> New Topic
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Categories</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('community.forum.index') }}" class="block px-3 py-2 rounded-md {{ !request()->routeIs('community.forum.category') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                            All Topics
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('community.forum.category', $category->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-md {{ request()->is('*category/' . $category->slug) ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>{{ $category->name }}</span>
                                <span class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $category->posts_count ?? 0 }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="divide-y divide-gray-100">
                        @forelse($posts as $post)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 text-center">
                                        <div class="bg-gray-100 rounded-lg p-2 min-w-[60px]">
                                            <span class="block text-lg font-bold text-gray-700">{{ $post->like_count }}</span>
                                            <span class="block text-xs text-gray-500">Likes</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($post->is_pinned)
                                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-thumbtack mr-1"></i> Pinned
                                                </span>
                                            @endif
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $post->category->name }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Posted by <span class="font-medium text-gray-700">{{ $post->user->name }}</span> {{ $post->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                            <a href="{{ route('community.forum.show', $post->slug) }}" class="hover:text-indigo-600">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                            {{ Str::limit(strip_tags($post->content), 180) }}
                                        </p>
                                        <div class="flex items-center gap-6 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-comment-alt"></i> {{ $post->replies_count }} replies
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-eye"></i> {{ $post->view_count }} views
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <i class="fas fa-inbox text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No topics found</h3>
                                <p class="text-gray-500 mt-1">Be the first to create a topic in this category!</p>
                                <a href="{{ route('community.forum.create') }}" class="inline-flex items-center mt-4 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Create Topic
                                </a>
                            </div>
                        @endforelse
                    </div>
                    @if($posts->hasPages())
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
