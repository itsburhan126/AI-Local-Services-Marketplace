@extends('layouts.customer')

@section('title', 'Success Stories')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <span class="text-indigo-600 font-semibold tracking-wide uppercase text-sm mb-2 block">Community Spotlight</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6 font-display">
                Success Stories
            </h1>
            <p class="text-xl text-gray-500 max-w-3xl mx-auto">
                Inspiring stories from the people who use Findlancer to build their businesses and bring their ideas to life.
            </p>
        </div>
    </div>

    <!-- Stories Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($stories->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($stories as $story)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    @if($story->image_path)
                        <img src="{{ asset('storage/' . $story->image_path) }}" alt="{{ $story->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-indigo-50 flex items-center justify-center text-indigo-200">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-gray-900 shadow-sm">
                        {{ $story->type }}
                    </div>
                </div>
                <div class="p-8 flex-1 flex flex-col">
                    <div class="flex items-center gap-4 mb-6">
                        @if($story->avatar_path)
                            <img src="{{ asset('storage/' . $story->avatar_path) }}" alt="{{ $story->name }}" class="w-12 h-12 rounded-full border-2 border-white shadow-sm object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 border-2 border-white shadow-sm">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $story->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $story->role }}</p>
                        </div>
                    </div>
                    <blockquote class="text-gray-600 italic mb-6 flex-1">
                        "{{ $story->quote }}"
                    </blockquote>
                    <div class="border-t border-gray-100 pt-6 mt-auto">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Service: <strong>{{ $story->service_category }}</strong></span>
                            @if($story->story_content)
                                <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700">Read Story <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-500 mx-auto mb-4">
                <i class="fas fa-book-open text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No Stories Yet</h3>
            <p class="text-gray-500">Check back soon for inspiring stories from our community.</p>
        </div>
        @endif
    </div>
</div>
@endsection
