@extends('layouts.customer')

@section('title', 'Findlancer Guides')

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    <!-- Header -->
    <div class="bg-indigo-900 py-16 text-center">
        <h1 class="text-4xl font-extrabold text-white tracking-tight mb-4 font-display">Findlancer Guides</h1>
        <p class="text-xl text-indigo-200 max-w-2xl mx-auto">
            Tutorials, tips, and resources to help you succeed on our platform.
        </p>
        
        <!-- Search -->
        <div class="max-w-xl mx-auto mt-8 px-4">
            <form action="{{ route('guides') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guides..." class="w-full pl-12 pr-4 py-4 rounded-full shadow-lg border-0 focus:ring-2 focus:ring-emerald-400 text-gray-800">
                <div class="absolute left-4 top-4 text-gray-400">
                    <i class="fas fa-search text-lg"></i>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('guides', ['category' => $category]) }}" class="bg-white p-6 rounded-xl shadow-md text-center hover:-translate-y-1 transition-transform duration-300">
                @if($category == 'Freelancer')
                    <i class="fas fa-rocket text-3xl text-indigo-500 mb-3"></i>
                @elseif($category == 'Client')
                    <i class="fas fa-bullhorn text-3xl text-emerald-500 mb-3"></i>
                @elseif($category == 'Platform')
                    <i class="fas fa-file-invoice-dollar text-3xl text-orange-500 mb-3"></i>
                @else
                    <i class="fas fa-book text-3xl text-blue-500 mb-3"></i>
                @endif
                <h3 class="font-bold text-gray-800">{{ $category }}</h3>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Latest Articles -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 font-display">Latest Articles</h2>
        
        @if($guides->count() > 0)
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($guides as $guide)
            <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="h-48 bg-gray-200 relative">
                    @if($guide->image_path)
                        <img src="{{ asset('storage/' . $guide->image_path) }}" alt="{{ $guide->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                    @endif
                    <span class="absolute top-4 right-4 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $guide->category }}</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-indigo-600 cursor-pointer">
                        <a href="{{ route('guides.show', $guide->slug) }}">{{ $guide->title }}</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ $guide->excerpt }}
                    </p>
                    <a href="{{ route('guides.show', $guide->slug) }}" class="text-indigo-600 font-semibold text-sm hover:underline">Read Article</a>
                </div>
            </article>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $guides->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-search text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">No guides found</h3>
            <p class="text-gray-500">Try adjusting your search or category filter.</p>
            <a href="{{ route('guides') }}" class="inline-block mt-4 text-indigo-600 font-bold hover:underline">View All Guides</a>
        </div>
        @endif
    </div>
</div>
@endsection
