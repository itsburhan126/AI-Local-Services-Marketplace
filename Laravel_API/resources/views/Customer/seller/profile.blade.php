<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} - Professional Profile | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Macondo&family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                        display: ['Macondo', 'cursive'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981', 
                            600: '#059669', 
                            700: '#047857', 
                            900: '#064e3b', 
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-full font-body text-slate-600 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation (Glassmorphism & Sticky) -->
    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Left: Logo & Search -->
                <div class="flex items-center gap-8 flex-1">
                    <!-- Logo -->
                    <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-black rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">f</div>
                        <span class="text-2xl font-bold text-gray-900 tracking-tight font-display group-hover:text-black transition-colors">findlancer</span>
                    </a>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden lg:flex flex-1 max-w-xl relative group">
                        <input type="text" 
                               class="block w-full pl-4 pr-14 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black/5 focus:border-black transition-all duration-200 sm:text-sm shadow-sm" 
                               placeholder="What service are you looking for today?">
                        <button class="absolute inset-y-0 right-0 px-4 bg-black rounded-r-lg flex items-center justify-center text-white hover:bg-gray-800 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right: Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-sm font-semibold text-gray-500 hover:text-black transition-colors relative group">Explore</a>
                        <a href="#" class="text-sm font-semibold text-gray-500 hover:text-black transition-colors relative group">Orders</a>
                        <a href="#" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors">Switch to Selling</a>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 focus:outline-none group">
                            <div class="relative">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-700 font-bold text-sm border border-gray-200 shadow-sm group-hover:shadow-md transition-all">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-white"></div>
                            </div>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 rounded-xl shadow-xl py-2 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">Settings</a>
                            </div>
                            <div class="border-t border-gray-50 py-1">
                                <form method="POST" action="{{ route('customer.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar: Provider Info -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden p-6 text-center relative">
                    <!-- Online Status -->
                    <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                        Online
                    </div>

                    <div class="relative w-32 h-32 mx-auto mb-4 group">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : ($user->providerProfile && $user->providerProfile->logo ? asset('storage/' . $user->providerProfile->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128') }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg group-hover:scale-105 transition-transform duration-300">
                        @if($user->providerProfile && $user->providerProfile->seller_level)
                        <div class="absolute bottom-1 right-1 bg-yellow-400 text-white text-xs font-bold px-2 py-0.5 rounded shadow-sm border border-white">
                            {{ $user->providerProfile->seller_level }}
                        </div>
                        @endif
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h1>
                    <p class="text-gray-500 text-sm mb-4">{{ $user->providerProfile->headline ?? 'Professional Freelancer' }}</p>

                    <!-- Rating -->
                    <div class="flex items-center justify-center gap-2 mb-6">
                        <div class="flex text-yellow-400">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="font-bold text-gray-900">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-gray-400 text-sm">({{ $totalReviews }} reviews)</span>
                    </div>

                    <button class="w-full bg-black text-white font-bold py-3 rounded-xl hover:bg-gray-800 transition-colors mb-6 shadow-lg shadow-black/10">
                        Contact Me
                    </button>

                    <!-- Stats -->
                    <div class="border-t border-gray-100 pt-6 text-left space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-map-marker-alt mr-2"></i>From</span>
                            <span class="font-bold text-gray-900">{{ $user->providerProfile->country ?? 'Global' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Member since</span>
                            <span class="font-bold text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Avg. Response Time</span>
                            <span class="font-bold text-gray-900">1 Hour</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Last Delivery</span>
                            <span class="font-bold text-gray-900">1 day ago</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($user->providerProfile && $user->providerProfile->about)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Description</h3>
                    <div class="text-gray-600 text-sm leading-relaxed prose prose-sm max-w-none">
                        {!! nl2br(e($user->providerProfile->about)) !!}
                    </div>
                </div>
                @endif

                <!-- Skills -->
                @if($user->providerProfile && !empty($user->providerProfile->skills))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Skills</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->providerProfile->skills as $skill)
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full border border-gray-200">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Languages -->
                @if($user->providerProfile && !empty($user->providerProfile->languages))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Languages</h3>
                    <ul class="space-y-2">
                        @foreach($user->providerProfile->languages as $lang)
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>
                                {{ $lang }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>

            <!-- Right Content -->
            <div class="lg:col-span-8 space-y-12">
                
                <!-- Active Gigs -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        Active Gigs 
                        <span class="bg-gray-100 text-gray-600 text-sm px-2 py-1 rounded-full">{{ $gigs->count() }}</span>
                    </h2>
                    
                    @if($gigs->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($gigs as $gig)
                                @include('Customer.components.gig-card', ['gig' => $gig])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                            <p class="text-gray-500">No active gigs found.</p>
                        </div>
                    @endif
                </section>

                <!-- Reviews -->
                <section>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Reviews as Seller</h2>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 text-lg">{{ number_format($averageRating, 1) }}</span>
                            <div class="flex text-yellow-400">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-5 h-5 {{ $i < round($averageRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-gray-500">({{ $totalReviews }})</span>
                        </div>
                    </div>

                    @if($user->reviews && $user->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($user->reviews as $review)
                                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" class="w-12 h-12 rounded-full border border-gray-100" alt="">
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                                <span class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="flex text-yellow-400 text-sm">
                                                    @for($i=0; $i<5; $i++)
                                                        <svg class="w-4 h-4 {{ $i < $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endfor
                                                </div>
                                                <span class="font-medium text-gray-900 text-sm">{{ number_format($review->rating, 1) }}</span>
                                            </div>
                                            <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
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
                                $allReviews = $allReviews->merge($gig->reviews);
                            }
                            $allReviews = $allReviews->sortByDesc('created_at')->take(5);
                         @endphp
                         
                         @if($allReviews->count() > 0)
                            <div class="space-y-6">
                                @foreach($allReviews as $review)
                                    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0">
                                                <img src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" class="w-12 h-12 rounded-full border border-gray-100" alt="">
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                                    <span class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 mb-3">
                                                    <div class="flex text-yellow-400 text-sm">
                                                        @for($i=0; $i<5; $i++)
                                                            <svg class="w-4 h-4 {{ $i < $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                        @endfor
                                                    </div>
                                                    <span class="font-medium text-gray-900 text-sm">{{ number_format($review->rating, 1) }}</span>
                                                    <span class="text-xs text-gray-400 ml-2">via {{ $review->gig->title ?? 'Gig' }}</span>
                                                </div>
                                                <p class="text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                         @else
                            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                                <p class="text-gray-500 font-medium">No reviews yet.</p>
                            </div>
                         @endif
                    @endif
                </section>

            </div>
        </div>
    </main>

</body>
</html>
