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

        <!-- 3. Recently Viewed (New) -->
        @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
            <div class="group/section" x-data="carousel">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 font-display">Recently Viewed</h2>
                    <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">See All</a>
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
                        @foreach($recentlyViewed as $gig)
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

        <!-- Single Promotional Banner (New) -->
        @php
            // Fallback: If controller didn't pass it, try to fetch it directly
            if (!isset($singleBanner) || !$singleBanner) {
                $singleBanner = \App\Models\Banner::where('type', 'promo_large')->where('status', 1)->first();
            }
        @endphp

        @if(isset($singleBanner) && $singleBanner)
            <div class="relative w-full rounded-3xl overflow-hidden shadow-2xl my-16 group block bg-gray-900 border border-gray-800/50 min-h-[300px] md:min-h-[400px]">
                 @php
                    $img = !empty($singleBanner->image_path) ? $singleBanner->image_path : $singleBanner->image;
                    
                    // Handle full URL vs relative path
                    if (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])) {
                        $src = $img;
                    } else {
                        // Ensure we strip any existing 'storage/' prefix to avoid duplication
                        $cleanPath = \Illuminate\Support\Str::replaceFirst('storage/', '', $img);
                        
                        // Use relative path to avoid IP mismatch issues in local environment
                        $src = '/storage/' . $cleanPath;
                    }
                @endphp
                
                <a href="{{ $singleBanner->link ?? '#' }}" class="block relative h-full min-h-[300px] md:min-h-[400px]">
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-gray-900 overflow-hidden rounded-3xl">
                        <img src="{{ $src }}" 
                             alt="{{ $singleBanner->title ?? 'Promotion' }}" 
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/1200x400?text=Promo+Image';"
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-in-out">
                    </div>
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/70 to-transparent opacity-90"></div>
                    
                    <!-- Content Container -->
                    <div class="absolute inset-0 flex items-center px-8 md:px-16 lg:px-24">
                        <div class="max-w-2xl transform transition-all duration-700 ease-out translate-y-0 group-hover:-translate-y-1">
                            
                            <!-- Badge/Tag -->
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 backdrop-blur-md border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-widest mb-6 shadow-lg shadow-emerald-900/20">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Featured Offer
                            </div>

                            @if($singleBanner->title)
                                <h3 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 font-display leading-tight drop-shadow-xl tracking-tight">
                                    {{ $singleBanner->title }}
                                </h3>
                            @endif
                            
                            @if($singleBanner->subtitle)
                                <p class="text-gray-300 text-lg md:text-xl mb-10 font-light leading-relaxed max-w-lg drop-shadow-md">
                                    {{ $singleBanner->subtitle }}
                                </p>
                            @endif
                            
                            @if($singleBanner->button_text)
                                <span class="group/btn inline-flex items-center gap-3 bg-white text-gray-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-emerald-500 hover:text-white transition-all duration-300 shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_30px_rgba(16,185,129,0.5)] active:scale-95">
                                    {{ $singleBanner->button_text }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
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

        <!-- 9. What Sparks Your Interest -->
        @if(isset($interests) && $interests->count() > 0)
            <div x-data="{
                scrollContainer: null,
                init() {
                    this.scrollContainer = this.$refs.list;
                    if (this.scrollContainer) {
                        this.scrollContainer.classList.add('cursor-grab');
                        this.scrollContainer.style.scrollBehavior = 'smooth';
                    }
                },
                scrollLeft() {
                    if (this.scrollContainer) {
                        this.scrollContainer.scrollBy({ left: -380, behavior: 'smooth' });
                    }
                },
                scrollRight() {
                    if (this.scrollContainer) {
                        this.scrollContainer.scrollBy({ left: 380, behavior: 'smooth' });
                    }
                },
                isDown: false,
                startX: 0,
                scrollPos: 0,
                start(e) {
                    if (!this.scrollContainer) return;
                    this.isDown = true;
                    this.startX = e.pageX - this.scrollContainer.offsetLeft;
                    this.scrollPos = this.scrollContainer.scrollLeft;
                    this.scrollContainer.classList.add('cursor-grabbing');
                    this.scrollContainer.classList.remove('cursor-grab');
                    this.scrollContainer.style.scrollBehavior = 'auto';
                },
                stop() {
                    if (!this.scrollContainer) return;
                    this.isDown = false;
                    this.scrollContainer.classList.remove('cursor-grabbing');
                    this.scrollContainer.classList.add('cursor-grab');
                    this.scrollContainer.style.scrollBehavior = 'smooth';
                },
                move(e) {
                    if (!this.isDown || !this.scrollContainer) return;
                    e.preventDefault();
                    const x = e.pageX - this.scrollContainer.offsetLeft;
                    const walk = (x - this.startX) * 1.5;
                    this.scrollContainer.scrollLeft = this.scrollPos - walk;
                },
                toggleInterest(id) {
                    const el = this.$refs['interest-wrapper-' + id];
                    if (!el) return;

                    // Optimistic UI: Hide the element immediately with a transition
                    el.style.transition = 'all 0.3s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'scale(0.9)';
                    
                    setTimeout(() => {
                         el.style.minWidth = '0px';
                         el.style.width = '0px';
                         el.style.display = 'none';
                    }, 300);
                    
                    fetch('{{ route('customer.interests.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ category_id: id })
                    }).then(res => res.json())
                    .then(data => {
                        if(data.status !== 'success') {
                            // Revert if failed
                            el.style.display = 'block';
                            el.style.minWidth = '';
                            el.style.width = '';
                            setTimeout(() => {
                                el.style.opacity = '1';
                                el.style.transform = 'scale(1)';
                            }, 50);
                        }
                    }).catch(err => {
                        el.style.display = 'block';
                        el.style.minWidth = '';
                        el.style.width = '';
                         setTimeout(() => {
                                el.style.opacity = '1';
                                el.style.transform = 'scale(1)';
                            }, 50);
                    });
                }
            }" class="group/section bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-50 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 font-display mb-2">What Sparks Your Interest?</h2>
                            <p class="text-gray-500 text-base">Select topics to personalize your feed.</p>
                        </div>
                        <a href="{{ route('customer.interests.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-4 py-2 rounded-full transition-colors">See All</a>
                    </div>
                    
                    <div class="relative">
                        <!-- Left Navigation Button -->
                        <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-30 p-2 bg-white shadow-lg rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -ml-4 hidden md:flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div x-ref="list" 
                             @mousedown="start"
                             @mouseleave="stop"
                             @mouseup="stop"
                             @mousemove="move"
                             class="flex overflow-x-auto gap-6 pb-4 scrollbar-hide -mx-4 px-4 snap-x snap-mandatory">
                            
                            @foreach($interests as $interest)
                                <div x-ref="interest-wrapper-{{ $interest->id }}" class="snap-center shrink-0 min-w-[320px] w-[320px] md:min-w-[380px] md:w-[380px] transition-all duration-300">
                                    <button @click="@auth toggleInterest({{ $interest->id }}) @else window.location.href = '{{ route('customer.login') }}' @endauth"
                                            class="w-full group/card flex items-center justify-between p-6 bg-white border border-gray-100 rounded-3xl hover:shadow-lg hover:shadow-emerald-50 transition-all duration-300 relative overflow-hidden">
                                        
                                        <div class="flex items-center gap-4 z-10">
                                            <div class="w-10 h-10 flex items-center justify-center text-gray-400 group-hover/card:text-emerald-500 transition-colors relative">
                                                @php
                                                    $imagePath = $interest->image ?? $interest->icon;
                                                    $iconSrc = null;
                                                    if ($imagePath) {
                                                        if (\Illuminate\Support\Str::startsWith($imagePath, ['http', 'https'])) {
                                                            $iconSrc = $imagePath;
                                                        } else {
                                                            $iconSrc = asset('storage/' . $imagePath);
                                                        }
                                                    }
                                                @endphp
                                                @if($iconSrc)
                                                    <img src="{{ $iconSrc }}" 
                                                         class="w-full h-full object-contain opacity-60 group-hover/card:opacity-100 transition-opacity absolute inset-0 bg-white" 
                                                         alt=""
                                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('opacity-0');">
                                                @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 transition-opacity {{ $iconSrc ? 'opacity-0' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-gray-800 text-lg group-hover/card:text-gray-900 transition-colors">{{ $interest->name }}</span>
                                        </div>
                                        
                                        <div class="z-10 px-5 py-2 rounded-full border border-gray-200 text-sm font-bold text-gray-700 group-hover/card:border-emerald-500 group-hover/card:text-emerald-600 group-hover/card:bg-emerald-50 transition-all flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Add
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Right Navigation Button -->
                        <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-30 p-2 bg-white shadow-lg rounded-full text-gray-700 hover:text-emerald-600 hover:scale-110 transition-all opacity-0 group-hover/section:opacity-100 border border-gray-100 -mr-4 hidden md:flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
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

        <!-- 11. Testimonials (Redesigned) -->
        <div class="relative py-12 mb-8">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-purple-50/50 to-white/50 rounded-3xl -z-10"></div>
            
            @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.testimonials.index') }}" target="_blank" class="absolute top-4 right-4 z-20 bg-gray-900 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg hover:bg-gray-800 hover:scale-105 transition-all flex items-center gap-2 group">
                    <span class="bg-gray-700 p-1 rounded-full group-hover:bg-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </span>
                    Manage Testimonials
                </a>
            @endif

            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 font-display mb-3">People Love Findlancer</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Don't just take our word for it. Here's what our community has to say about their experience.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 md:px-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-8 rounded-2xl shadow-[0_2px_20px_rgba(0,0,0,0.04)] border border-gray-100 hover:-translate-y-1 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all duration-300 relative group h-full flex flex-col">
                        <!-- Decorative Quote -->
                        <div class="absolute top-6 right-8 text-indigo-100 text-6xl font-serif leading-none select-none group-hover:text-indigo-200 transition-colors">"</div>
                        
                        <!-- Rating -->
                        <div class="flex gap-1 mb-6 text-amber-400 text-sm">
                             @for($i = 0; $i < $testimonial->rating; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                             @endfor
                        </div>

                        <!-- Content -->
                        <blockquote class="text-gray-600 leading-relaxed mb-8 relative z-10 flex-grow">
                            "{{ $testimonial->text }}"
                        </blockquote>

                        <!-- Author -->
                        <div class="flex items-center gap-4 mt-auto pt-6 border-t border-gray-50">
                            <div class="relative">
                                <div class="absolute inset-0 bg-indigo-100 rounded-full blur-sm group-hover:blur-md transition-all"></div>
                                <img src="{{ Str::startsWith($testimonial->image, 'http') ? $testimonial->image : ($testimonial->image ? asset('storage/' . $testimonial->image) : 'https://ui-avatars.com/api/?name=' . urlencode($testimonial->name) . '&background=random') }}" 
                                     class="w-12 h-12 rounded-full object-cover relative border-2 border-white shadow-sm" 
                                     alt="{{ $testimonial->name }}"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($testimonial->name) }}&background=random';">
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm group-hover:text-indigo-600 transition-colors">{{ $testimonial->name }}</h4>
                                <p class="text-xs text-gray-400 font-medium">{{ $testimonial->role }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 12. Trust & Safety (Ultra Light Glass UI) -->
        <div class="relative rounded-[2.5rem] p-8 md:p-16 mb-16 overflow-hidden group/section">
            <!-- Dynamic Animated Background (Light) -->
            <div class="absolute inset-0 bg-slate-50/50">
                <!-- Animated Orbs (Pastel) -->
                <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-300/20 rounded-full blur-[100px] animate-pulse mix-blend-multiply"></div>
                <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-emerald-300/20 rounded-full blur-[100px] animate-pulse mix-blend-multiply" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-purple-300/10 rounded-full blur-[120px]"></div>
                
                <!-- Grid Pattern Overlay -->
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                <div class="absolute inset-0" style="background-image: linear-gradient(rgba(0, 0, 0, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 0, 0, 0.02) 1px, transparent 1px); background-size: 50px 50px;"></div>
            </div>
            
            @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.trust-safety.index') }}" target="_blank" class="absolute top-8 right-8 z-30 bg-white/80 text-slate-700 px-5 py-2.5 rounded-full text-xs font-bold backdrop-blur-md border border-slate-200 hover:bg-white hover:scale-105 transition-all flex items-center gap-2 group cursor-pointer shadow-sm">
                    <span class="bg-slate-100 p-1.5 rounded-full group-hover:bg-slate-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </span>
                    Edit Content
                </a>
            @endif

            <div class="relative z-10 max-w-7xl mx-auto">
                <div class="text-center mb-16 space-y-4">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 border border-white/40 backdrop-blur-md shadow-sm mb-4 animate-bounce-slow">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-emerald-600 text-xs font-bold tracking-widest uppercase">World Class Security</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-900 font-display drop-shadow-sm">
                        Trust & Safety
                    </h2>
                    <p class="text-slate-500 text-lg max-w-2xl mx-auto leading-relaxed font-light">
                        Experience peace of mind with our enterprise-grade protection protocols and verified community standards.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 perspective-1000">
                    @foreach($trustSafetyItems->take(4) as $index => $item)
                        <div class="group relative" style="animation: fadeUp 0.6s ease-out forwards {{ $index * 0.1 }}s; opacity: 0;">
                            <!-- Glow Effect on Hover -->
                            <div class="absolute -inset-0.5 bg-gradient-to-br from-indigo-200 to-emerald-200 rounded-3xl blur opacity-0 group-hover:opacity-60 transition duration-500"></div>
                            
                            <!-- Glass Card (Light) -->
                            <div class="relative h-full bg-white/60 backdrop-blur-xl border border-white/60 rounded-3xl p-8 hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)]">
                                <!-- Floating Icon Container -->
                                <div class="relative mb-8 group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-emerald-100 blur-xl rounded-full opacity-50"></div>
                                    <div class="relative w-20 h-20 bg-gradient-to-br from-white to-slate-50 rounded-2xl border border-white/80 flex items-center justify-center shadow-lg">
                                        <i class="{{ $item->icon }} text-3xl text-transparent bg-clip-text bg-gradient-to-br from-indigo-600 to-emerald-600"></i>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-slate-800 mb-4 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $item->title }}</h3>
                                
                                <p class="text-slate-500 text-sm leading-relaxed group-hover:text-slate-600 transition-colors">
                                    {{ Str::limit($item->description, 100) }}
                                </p>

                                <!-- Shine Effect -->
                                <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
                                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-[100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <style>
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-bounce-slow {
                animation: bounce 3s infinite;
            }
            .perspective-1000 {
                perspective: 1000px;
            }
        </style>

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