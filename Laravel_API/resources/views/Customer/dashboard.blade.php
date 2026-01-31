@extends('layouts.customer')

@section('title', 'Dashboard')

@section('content')
    <!-- Main Content -->
    <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
        
        <!-- 1. Banners Section -->
        @if(isset($banners) && $banners->count() > 0)
            <div class="relative w-full rounded-2xl overflow-hidden shadow-2xl group select-none cursor-grab active:cursor-grabbing" 
                 x-data="{ 
                     activeSlide: 0, 
                     slides: {{ $banners->count() }}, 
                     timer: null,
                     startX: 0,
                     endX: 0,
                     isDragging: false,
                     startAutoPlay() {
                         if (this.timer) clearInterval(this.timer);
                         this.timer = setInterval(() => { this.next() }, 5000);
                     },
                     stopAutoPlay() {
                         if (this.timer) clearInterval(this.timer);
                     },
                     next() {
                         this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1;
                     },
                     prev() {
                         this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1;
                     },
                     handleTouchStart(e) {
                         this.startX = e.touches[0].clientX;
                         this.stopAutoPlay();
                     },
                     handleTouchMove(e) {
                         this.endX = e.touches[0].clientX;
                     },
                     handleTouchEnd() {
                         if (!this.startX || !this.endX) return;
                         let diff = this.startX - this.endX;
                         if (diff > 50) this.next();
                         if (diff < -50) this.prev();
                         this.startX = 0;
                         this.endX = 0;
                         this.startAutoPlay();
                     },
                     handleMouseDown(e) {
                         this.isDragging = true;
                         this.startX = e.clientX;
                         this.stopAutoPlay();
                     },
                     handleMouseMove(e) {
                         if (!this.isDragging) return;
                         this.endX = e.clientX;
                     },
                     handleMouseUp() {
                         if (!this.isDragging) return;
                         this.isDragging = false;
                         if (this.startX && this.endX) {
                             let diff = this.startX - this.endX;
                             if (diff > 50) this.next();
                             if (diff < -50) this.prev();
                         }
                         this.startX = 0;
                         this.endX = 0;
                         this.startAutoPlay();
                     }
                 }" 
                 x-init="startAutoPlay()"
                 @mouseenter="stopAutoPlay()" 
                 @mouseleave="startAutoPlay()"
                 @touchstart="handleTouchStart"
                 @touchmove="handleTouchMove"
                 @touchend="handleTouchEnd"
                 @mousedown="handleMouseDown"
                 @mousemove="handleMouseMove"
                 @mouseup="handleMouseUp"
                 @mouseleave.self="handleMouseUp">
                 
                <div class="relative h-[200px] md:h-[350px]">
                    @foreach($banners as $index => $banner)
                        <div x-show="activeSlide === {{ $index }}" 
                             x-transition:enter="transition transform duration-700 ease-out" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100" 
                             x-transition:leave="transition transform duration-700 ease-in" 
                             x-transition:leave-start="opacity-100 scale-100" 
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute inset-0 w-full h-full">
                            @php
                                $img = $banner->image_path ?? $banner->image;
                                $src = Str::startsWith($img, ['http://', 'https://']) ? $img : asset('storage/' . $img);
                            @endphp
                            <img src="{{ $src }}" class="w-full h-full object-cover pointer-events-none" alt="Banner">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                        </div>
                    @endforeach
                    
                    <!-- Prev Button -->
                    <button x-show="slides > 1" @click.stop="prev()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 focus:outline-none z-20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button x-show="slides > 1" @click.stop="next()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-3 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 focus:outline-none z-20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    
                    <!-- Indicators -->
                    <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10" x-show="slides > 1">
                        @foreach($banners as $index => $banner)
                            <button @click.stop="activeSlide = {{ $index }}" :class="{'bg-emerald-500 w-8': activeSlide === {{ $index }}, 'bg-white/50 w-2 hover:bg-white/80': activeSlide !== {{ $index }}}" class="h-2 rounded-full transition-all duration-300 focus:outline-none"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- 2. Flash Sale (New) -->
        @if($flashSaleGigs->count() > 0)
            <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-3xl p-8 border border-red-100" x-data="carousel">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <h2 class="text-2xl font-bold text-gray-900 font-display flex items-center gap-2">
                            <span class="text-red-500">⚡</span> Flash Sale
                        </h2>
                        <!-- Simple Countdown Timer (Static for now, can be dynamic) -->
                        <div class="hidden md:flex items-center gap-2 text-sm font-medium text-red-600 bg-white px-3 py-1 rounded-full shadow-sm">
                            <span>Ending soon:</span>
                            <span class="font-mono font-bold">05:23:12</span>
                        </div>
                    </div>
                    <a href="{{ route('customer.gigs.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center gap-1 transition-colors">
                        View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div x-ref="list"
                     @mousedown="start"
                     @mouseleave="stop"
                     @mouseup="stop"
                     @mousemove="move"
                     class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide -mx-4 px-4">
                    @foreach($flashSaleGigs as $gig)
                        @include('Customer.components.gig-card', ['gig' => $gig, 'cardWidth' => 'min-w-[340px] w-[340px]'])
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4. Popular Gigs -->
        <div class="group/section" x-data="carousel">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 font-display">Popular Gigs</h2>
                <a href="{{ route('customer.gigs.index', ['sort' => 'popular']) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">See All</a>
            </div>
            
            <div class="relative">
                <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -ml-5 hidden md:flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div x-ref="list" 
                     @mousedown="start"
                     @mouseleave="stop"
                     @mouseup="stop"
                     @mousemove="move"
                     class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide -mx-4 px-4">
                    @foreach($popularGigs as $gig)
                        @include('Customer.components.gig-card', ['gig' => $gig, 'cardWidth' => 'min-w-[340px] w-[340px]'])
                    @endforeach
                </div>

                <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -mr-5 hidden md:flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        @if($recentlySaved->count() > 0)
            <div class="group/section" x-data="carousel">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 font-display">Recently Saved</h2>
                
                <div class="relative">
                    <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -ml-5 hidden md:flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div x-ref="list" 
                     @mousedown="start"
                     @mouseleave="stop"
                     @mouseup="stop"
                     @mousemove="move"
                     class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide -mx-4 px-4">
                    @foreach($recentlySaved as $gig)
                        @include('Customer.components.gig-card', ['gig' => $gig, 'cardWidth' => 'min-w-[340px] w-[340px]'])
                    @endforeach
                </div>

                    <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -mr-5 hidden md:flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- 9. What Sparks Your Interest (New) -->
        @if($interestsGigs->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 font-display">What Sparks Your Interest</h2>
                    <a href="{{ route('customer.gigs.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">See All</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($interestsGigs as $gig)
                        @include('Customer.components.gig-card', ['gig' => $gig])
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 10. Referral Card (New) -->
        <div class="bg-indigo-600 rounded-3xl p-8 md:p-12 text-center md:text-left relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-500/30 rounded-full blur-3xl -ml-10 -mb-10"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-xl">
                    <h2 class="text-3xl font-bold text-white font-display mb-4">Invite Friends & Earn Rewards</h2>
                    <p class="text-indigo-100 text-lg mb-8">Share the love! Invite friends to Findlancer and you'll both get $20 off your next order.</p>
                    <button class="bg-white text-indigo-600 px-8 py-3 rounded-xl font-bold hover:bg-indigo-50 transition-colors shadow-lg">Invite Friends</button>
                </div>
                <div class="hidden md:block">
                    <!-- Illustration placeholder -->
                    <div class="w-48 h-48 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/30">
                        <span class="text-6xl">🎁</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 11. Testimonials (New) -->
        <div class="bg-gray-50 rounded-3xl p-8 md:p-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 font-display text-center">People Love Findlancer</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $testimonial['image'] }}" class="w-12 h-12 rounded-full object-cover" alt="{{ $testimonial['name'] }}">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $testimonial['name'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"{{ $testimonial['text'] }}"</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 12. Trust & Safety (New) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center py-8 border-t border-gray-100">
            <div class="p-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">🛡️</div>
                <h3 class="font-bold text-gray-900 mb-2">Secure Payments</h3>
                <p class="text-sm text-gray-500">Your money is held safely until you approve the work.</p>
            </div>
            <div class="p-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">⭐</div>
                <h3 class="font-bold text-gray-900 mb-2">Quality Work</h3>
                <p class="text-sm text-gray-500">Check ratings and reviews to hire the best talent.</p>
            </div>
            <div class="p-4">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">🎧</div>
                <h3 class="font-bold text-gray-900 mb-2">24/7 Support</h3>
                <p class="text-sm text-gray-500">Our support team is always here to help you.</p>
            </div>
        </div>

        <!-- 13. Inspired by Browsing History (New) -->
        @if($inspiredByHistory->count() > 0)
            <div class="group/section" x-data="carousel">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 font-display">Inspired by your browsing history</h2>
                
                <div class="relative">
                    <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -ml-5 hidden md:flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div x-ref="list" 
                     @mousedown="start"
                     @mouseleave="stop"
                     @mouseup="stop"
                     @mousemove="move"
                     class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide -mx-4 px-4">
                    @foreach($inspiredByHistory as $gig)
                        @include('Customer.components.gig-card', ['gig' => $gig, 'cardWidth' => 'min-w-[340px] w-[340px]'])
                    @endforeach
                </div>

                    <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -mr-5 hidden md:flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- 14. New Gigs (New) -->
        <div class="group/section" x-data="carousel">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900 font-display">New Gigs</h2>
                <a href="{{ route('customer.gigs.index', ['sort' => 'newest']) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">See All</a>
            </div>
            
            <div class="relative">
                <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -ml-5 hidden md:flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div x-ref="list" 
                     @mousedown="start"
                     @mouseleave="stop"
                     @mouseup="stop"
                     @mousemove="move"
                     class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide -mx-4 px-4">
                    @foreach($newGigs as $gig)
                        @include('Customer.components.gig-card', ['gig' => $gig, 'cardWidth' => 'min-w-[340px] w-[340px]'])
                    @endforeach
                </div>

                <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-30 p-3 bg-white shadow-xl rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -mr-5 hidden md:flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

    </main>
@endsection