@extends('layouts.customer')

@section('title', $user->name . ' - Professional Profile')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar: Provider Info (Sticky) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="lg:sticky lg:top-24 space-y-6">
                    
                    <!-- Profile Card -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-soft overflow-hidden p-8 text-center relative group hover:shadow-premium transition-all duration-300">
                        <!-- Online Status -->
                        <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Online
                        </div>

                        <div class="relative w-36 h-36 mx-auto mb-6">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : ($user->providerProfile && $user->providerProfile->logo ? asset('storage/' . $user->providerProfile->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff&size=128') }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-full h-full rounded-full object-cover border-[6px] border-white shadow-xl group-hover:scale-105 transition-transform duration-300">
                            
                            @if($user->providerProfile && $user->providerProfile->seller_level)
                            <div class="absolute bottom-2 right-2 bg-gradient-to-r from-amber-400 to-amber-500 text-white text-[10px] uppercase tracking-wider font-bold px-3 py-1 rounded-full shadow-sm border-2 border-white">
                                {{ $user->providerProfile->seller_level }}
                            </div>
                            @endif
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-2 font-display tracking-tight">{{ $user->name }}</h1>
                        <p class="text-gray-500 font-medium mb-6 flex items-center justify-center gap-2">
                            {{ $user->providerProfile->headline ?? 'Professional Freelancer' }}
                        </p>

                        <!-- Rating -->
                        <div class="flex items-center justify-center gap-3 mb-8 bg-gray-50/50 py-3 rounded-xl mx-4">
                            <div class="flex text-amber-400">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="font-bold text-gray-900 text-lg">{{ number_format($averageRating, 1) }}</span>
                                <span class="text-gray-400 text-sm">({{ $totalReviews }} reviews)</span>
                            </div>
                        </div>

                        <button class="w-full bg-black text-white font-bold py-3.5 rounded-xl hover:bg-gray-800 transition-all duration-300 mb-8 shadow-lg shadow-black/5 hover:shadow-black/20 flex items-center justify-center gap-2 group/btn">
                            <span>Contact Me</span>
                            <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>

                        <!-- Stats -->
                        <div class="border-t border-gray-100 pt-6 space-y-4">
                            <div class="flex justify-between items-center text-sm group/stat hover:bg-gray-50 p-2 rounded-lg transition-colors -mx-2">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-400 w-5 text-center"></i>
                                    From
                                </span>
                                <span class="font-bold text-gray-900">{{ $user->providerProfile->country ?? 'Global' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm group/stat hover:bg-gray-50 p-2 rounded-lg transition-colors -mx-2">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-user text-gray-400 w-5 text-center"></i>
                                    Member since
                                </span>
                                <span class="font-bold text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm group/stat hover:bg-gray-50 p-2 rounded-lg transition-colors -mx-2">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-400 w-5 text-center"></i>
                                    Avg. Response
                                </span>
                                <span class="font-bold text-gray-900">1 Hour</span>
                            </div>
                            <div class="flex justify-between items-center text-sm group/stat hover:bg-gray-50 p-2 rounded-lg transition-colors -mx-2">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <i class="fas fa-paper-plane text-gray-400 w-5 text-center"></i>
                                    Last Delivery
                                </span>
                                <span class="font-bold text-gray-900">1 day ago</span>
                            </div>
                        </div>
                    </div>

                    <!-- Skills & Languages -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-soft p-6 space-y-8">
                        @if($user->providerProfile && !empty($user->providerProfile->skills))
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-tools text-emerald-500"></i>
                                Skills
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->providerProfile->skills as $skill)
                                    <span class="px-3 py-1.5 bg-gray-50 text-gray-600 text-xs font-semibold rounded-lg border border-gray-200 hover:border-emerald-200 hover:text-emerald-700 hover:bg-emerald-50 transition-all cursor-default">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($user->providerProfile && !empty($user->providerProfile->languages))
                        <div class="pt-6 border-t border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-language text-blue-500"></i>
                                Languages
                            </h3>
                            <ul class="space-y-3">
                                @foreach($user->providerProfile->languages as $lang)
                                    <li class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                        <span class="w-2 h-2 rounded-full bg-blue-400 mr-3"></span>
                                        {{ $lang }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Right Content -->
            <div class="lg:col-span-8 space-y-10">
                
                <!-- About -->
                @if($user->providerProfile && $user->providerProfile->about)
                <section class="bg-white rounded-2xl border border-gray-100 shadow-soft p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 font-display">About Me</h3>
                    <div class="text-gray-600 leading-relaxed prose prose-slate max-w-none">
                        {!! nl2br(e($user->providerProfile->about)) !!}
                    </div>
                </section>
                @endif

                <!-- Active Gigs -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 font-display flex items-center gap-3">
                            Active Gigs 
                            <span class="bg-black text-white text-xs px-2.5 py-1 rounded-full font-sans">{{ $gigs->count() }}</span>
                        </h2>
                    </div>
                    
                    @if($gigs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($gigs as $gig)
                                @include('Customer.components.gig-card', ['gig' => $gig])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">No Active Gigs</h3>
                            <p class="text-gray-500">This seller hasn't posted any gigs yet.</p>
                        </div>
                    @endif
                </section>

                <!-- Reviews -->
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 font-display">Reviews as Seller</h2>
                        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-lg border border-gray-100 shadow-sm">
                            <span class="font-bold text-gray-900 text-xl">{{ number_format($averageRating, 1) }}</span>
                            <div class="flex text-amber-400">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-5 h-5 {{ $i < round($averageRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-gray-400 font-medium text-sm">({{ $totalReviews }})</span>
                        </div>
                    </div>

                    @if($user->reviews && $user->reviews->count() > 0)
                        <div class="grid gap-6">
                            @foreach($user->reviews as $review)
                                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-soft hover:shadow-md transition-all duration-300">
                                    <div class="flex gap-5">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $review->user && $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'User') . '&background=random' }}" 
                                                 class="w-14 h-14 rounded-full border-2 border-white shadow-md object-cover" alt="">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <h4 class="font-bold text-gray-900 text-lg">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <div class="flex text-amber-400 text-sm">
                                                            @for($i=0; $i<5; $i++)
                                                                <svg class="w-4 h-4 {{ $i < $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                            @endfor
                                                        </div>
                                                        <span class="font-bold text-gray-900 text-sm">{{ number_format($review->rating, 1) }}</span>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-400 bg-gray-50 px-3 py-1 rounded-full">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            
                                            <div class="relative">
                                                <svg class="absolute -top-2 -left-2 w-8 h-8 text-gray-100 transform -scale-x-100" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                                    <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                                </svg>
                                                <p class="text-gray-600 leading-relaxed text-lg italic pl-4">{{ $review->review ?? $review->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                         <!-- Fallback to showing reviews from gigs if direct reviews are empty -->
                         @php
                            $allReviews = collect();
                            foreach($gigs as $gig) {
                                if($gig->reviews) {
                                    $allReviews = $allReviews->merge($gig->reviews);
                                }
                            }
                            $allReviews = $allReviews->sortByDesc('created_at')->take(5);
                         @endphp
                         
                         @if($allReviews->count() > 0)
                            <div class="grid gap-6">
                                @foreach($allReviews as $review)
                                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-soft hover:shadow-md transition-all duration-300">
                                        <div class="flex gap-5">
                                            <div class="flex-shrink-0">
                                                <img src="{{ $review->user && $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'User') . '&background=random' }}" 
                                                     class="w-14 h-14 rounded-full border-2 border-white shadow-md object-cover" alt="">
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 text-lg">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                                        <div class="flex items-center gap-2 mt-1">
                                                            <div class="flex text-amber-400 text-sm">
                                                                @for($i=0; $i<5; $i++)
                                                                    <svg class="w-4 h-4 {{ $i < $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                                @endfor
                                                            </div>
                                                            <span class="font-bold text-gray-900 text-sm">{{ number_format($review->rating, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm text-gray-400 bg-gray-50 px-3 py-1 rounded-full">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                                
                                                <div class="relative">
                                                    <p class="text-gray-600 leading-relaxed pl-4">{{ $review->review ?? $review->comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                         @else
                            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-300">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">No Reviews Yet</h3>
                                <p class="text-gray-500">This seller hasn't received any reviews yet.</p>
                            </div>
                         @endif
                    @endif
                </section>

            </div>
        </div>
    </div>
</div>
@endsection
