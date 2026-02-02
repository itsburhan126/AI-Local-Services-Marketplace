@extends('layouts.customer')

@section('title', 'Community Hub')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Hero Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-4">
                Community Hub
            </h1>
            <p class="max-w-2xl mx-auto text-xl text-gray-500 mb-8">
                Connect, learn, and grow with our vibrant community of freelancers and clients.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('community.forum.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-comments mr-2"></i> Join Discussions
                </a>
                <a href="{{ route('community.events.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-2"></i> Browse Events
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Categories & Upcoming Events -->
            <div class="space-y-8">
                <!-- Categories -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Explore Categories</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($categories as $category)
                            <a href="{{ route('community.forum.category', $category->slug) }}" class="block px-6 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-indigo-500 mr-3">
                                            <i class="{{ $category->icon ?? 'fas fa-hashtag' }}"></i>
                                        </span>
                                        <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Upcoming Events Widget -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Upcoming Events</h3>
                        <a href="{{ route('community.events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($upcomingEvents as $event)
                            <div class="p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-indigo-50 rounded-lg p-3 text-center min-w-[60px]">
                                        <span class="block text-indigo-600 font-bold text-lg">{{ $event->start_date->format('d') }}</span>
                                        <span class="block text-indigo-500 text-xs uppercase">{{ $event->start_date->format('M') }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-md font-semibold text-gray-900">
                                            <a href="{{ route('community.events.show', $event->slug) }}" class="hover:underline">{{ $event->title }}</a>
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="far fa-clock mr-1"></i> {{ $event->start_date->format('h:i A') }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->is_online ? 'Online' : $event->location }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500">No upcoming events.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Discussions -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Recent Discussions</h3>
                        <a href="{{ route('community.forum.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Visit Forum</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentDiscussions as $post)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ $post->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&size=80' }}"
                                             alt="{{ $post->user->name }}"
                                             onerror="this.onerror=null;this.src='https://placehold.co/80x80?text=User';">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $post->category->name }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                            <a href="{{ route('community.forum.show', $post->slug) }}" class="hover:text-indigo-600">
                                                {{ $post->title }}
                                            </a>
                                        </h4>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-2">
                                            {{ Str::limit(strip_tags($post->content), 150) }}
                                        </p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-user"></i> {{ $post->user->name }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-comment-dots"></i> {{ $post->replies_count ?? 0 }} replies
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-eye"></i> {{ $post->view_count }} views
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">
                                <i class="fas fa-comments text-4xl mb-3 text-gray-300"></i>
                                <p>No discussions yet. Be the first to start one!</p>
                                <a href="{{ route('community.forum.create') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                                    Start a Topic
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
