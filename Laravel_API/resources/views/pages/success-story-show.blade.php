@extends('layouts.customer')

@section('title', $story->name . ' - Success Story')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Hero Section -->
    <div class="relative h-[60vh] min-h-[500px] w-full overflow-hidden bg-gray-900">
        @if($story->image_path)
            <img src="{{ asset('storage/' . $story->image_path) }}" alt="{{ $story->name }}" class="absolute inset-0 w-full h-full object-cover opacity-60" onerror="this.onerror=null;this.src='https://placehold.co/1920x1080?text=Success+Story';">
        @else
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-900 to-purple-900 opacity-90"></div>
        @endif
        
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-20">
            <a href="{{ route('success-stories') }}" class="inline-flex items-center text-white/80 hover:text-white mb-8 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Success Stories
            </a>
            
            <div class="flex items-end gap-6 md:gap-8">
                <!-- Avatar -->
                <div class="hidden md:block flex-shrink-0">
                    @if($story->avatar_path)
                        <img src="{{ asset('storage/' . $story->avatar_path) }}" alt="{{ $story->name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-xl object-cover" onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=User';">
                    @else
                        <div class="w-24 h-24 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 border-4 border-white shadow-xl text-3xl">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 rounded-full bg-indigo-600 text-white text-xs font-bold uppercase tracking-wider shadow-sm">
                            {{ $story->type }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider">
                            {{ $story->service_category }}
                        </span>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-bold text-white font-display mb-2 drop-shadow-lg">
                        {{ $story->name }}
                    </h1>
                    <p class="text-xl text-indigo-200 font-medium">{{ $story->role }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-gray-100">
            <!-- Mobile Avatar (visible only on small screens) -->
            <div class="md:hidden -mt-20 mb-8 flex justify-center">
                @if($story->avatar_path)
                    <img src="{{ asset('storage/' . $story->avatar_path) }}" alt="{{ $story->name }}" class="w-32 h-32 rounded-full border-4 border-white shadow-xl object-cover" onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=User';">
                @else
                    <div class="w-32 h-32 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 border-4 border-white shadow-xl text-4xl">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>

            <!-- Quote -->
            <blockquote class="text-2xl md:text-3xl font-serif italic text-gray-800 leading-relaxed text-center mb-12 relative">
                <span class="absolute top-0 left-0 transform -translate-x-4 -translate-y-4 text-6xl text-indigo-100 font-sans">"</span>
                {{ $story->quote }}
                <span class="absolute bottom-0 right-0 transform translate-x-4 translate-y-4 text-6xl text-indigo-100 font-sans">"</span>
            </blockquote>

            <!-- Story Body -->
            <div class="prose prose-lg prose-indigo mx-auto text-gray-600 leading-loose">
                {!! nl2br(e($story->story_content)) !!}
            </div>
            
            <!-- Call to Action -->
            <div class="mt-16 pt-8 border-t border-gray-100 text-center">
                <p class="text-gray-500 mb-6">Inspired by {{ $story->name }}'s journey?</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('customer.gigs.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                        Start Your Project
                    </a>
                    <a href="{{ route('customer.register') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                        Join as a Freelancer
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Spacer -->
    <div class="h-24"></div>
</div>
@endsection
