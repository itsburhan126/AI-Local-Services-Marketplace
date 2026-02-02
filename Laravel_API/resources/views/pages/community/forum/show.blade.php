@extends('layouts.customer')

@section('title', $post->title)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('community.forum.index') }}" class="text-gray-500 hover:text-indigo-600">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li><span class="text-gray-300">/</span></li>
                <li>
                    <a href="{{ route('community.forum.category', $post->category->slug) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">
                        {{ $post->category->name }}
                    </a>
                </li>
                <li><span class="text-gray-300">/</span></li>
                <li>
                    <span class="text-sm font-medium text-gray-900" aria-current="page">{{ Str::limit($post->title, 30) }}</span>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3 space-y-6">
                <!-- Original Post -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
                            <div class="flex items-center gap-2">
                                @if($post->is_pinned)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-thumbtack mr-1"></i> Pinned
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $post->category->name }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 text-center">
                                @if($post->user->profile_photo_url)
                                    <img class="h-12 w-12 rounded-full mx-auto" src="{{ $post->user->profile_photo_url }}" alt="">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold mx-auto">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900">{{ $post->user->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">Original Poster</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="prose max-w-none text-gray-800 mb-6">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                                <div class="flex items-center gap-6 pt-6 border-t border-gray-100">
                                    <button class="flex items-center gap-2 text-gray-500 hover:text-indigo-600 transition">
                                        <i class="far fa-thumbs-up"></i>
                                        <span>{{ $post->like_count }} Likes</span>
                                    </button>
                                    <span class="flex items-center gap-2 text-gray-500">
                                        <i class="far fa-eye"></i>
                                        <span>{{ $post->view_count }} Views</span>
                                    </span>
                                    <span class="flex items-center gap-2 text-gray-500">
                                        <i class="far fa-comment-alt"></i>
                                        <span>{{ $post->replies->count() }} Replies</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Replies -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $post->replies->count() }} Replies</h3>
                    
                    @foreach($post->replies as $reply)
                        <div class="bg-white rounded-lg shadow p-6" id="reply-{{ $reply->id }}">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    @if($reply->user->profile_photo_url)
                                        <img class="h-10 w-10 rounded-full" src="{{ $reply->user->profile_photo_url }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-gray-900">{{ $reply->user->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="prose max-w-none text-gray-800">
                                        {!! nl2br(e($reply->content)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Leave a Reply</h3>
                    @auth
                        <form action="{{ route('community.forum.reply', $post->slug) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="sr-only">Your Reply</label>
                                <textarea id="content" name="content" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Share your thoughts..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Post Reply
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <p class="text-gray-600 mb-4">You need to log in to post a reply.</p>
                            <a href="{{ route('customer.login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                Log In
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- About Community -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-900 mb-4">About Community</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Welcome to our community! Please keep discussions respectful and helpful.
                    </p>
                    <a href="{{ route('community.forum.create') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Create New Topic
                    </a>
                </div>

                <!-- Related Topics (Placeholder) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Recent Topics</h3>
                    <ul class="space-y-4">
                        @foreach($recentPosts as $recent)
                            <li>
                                <a href="{{ route('community.forum.show', $recent->slug) }}" class="group block">
                                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 truncate">{{ $recent->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ $recent->created_at->diffForHumans() }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
