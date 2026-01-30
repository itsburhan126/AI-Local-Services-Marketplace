<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | {{ config('app.name') }}</title>
    
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
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02)',
                        'glow': '0 0 15px rgba(16, 185, 129, 0.3)',
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
                        <a href="#" class="text-sm font-semibold text-gray-500 hover:text-black transition-colors relative group">
                            Explore
                            <span class="absolute inset-x-0 -bottom-1 h-0.5 bg-black transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </a>
                        <a href="#" class="text-sm font-semibold text-gray-500 hover:text-black transition-colors relative group">
                            Orders
                            <span class="absolute inset-x-0 -bottom-1 h-0.5 bg-black transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></span>
                        </a>
                        <a href="#" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors">Switch to Selling</a>
                    </div>
                    
                    <!-- Icons -->
                    <div class="flex items-center space-x-5 border-l border-gray-200 pl-6">
                        <button class="text-gray-400 hover:text-gray-900 transition-colors relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>
                        <button class="text-gray-400 hover:text-gray-900 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </button>
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
                        
                        <div x-show="open" @click.away="open = false" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-64 rounded-xl shadow-xl py-2 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">Post a Request</a>
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

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    <!-- Mobile Menu (Slide-over) -->
    <div class="relative z-50 lg:hidden" x-show="mobileMenuOpen" role="dialog" aria-modal="true" x-cloak>
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" x-show="mobileMenuOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <div class="fixed inset-0 z-50 flex">
            <div class="relative flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4 transition transform" x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" @click.away="mobileMenuOpen = false">
                <div class="flex items-center justify-between px-6 mb-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                         <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-lg">f</div>
                         <span class="text-xl font-bold text-gray-900 tracking-tight font-display">findlancer</span>
                    </a>
                    <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500" @click="mobileMenuOpen = false">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="mt-2 h-full overflow-y-auto px-6">
                    <div class="space-y-1 mb-8">
                        <a href="#" class="block rounded-md px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Explore</a>
                        <a href="#" class="block rounded-md px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Orders</a>
                        <a href="#" class="block rounded-md px-3 py-2.5 text-base font-semibold leading-7 text-emerald-600 hover:bg-emerald-50">Switch to Selling</a>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Categories</h3>
                        <div class="space-y-1">
                            @foreach($categories as $category)
                                <div x-data="{ expanded: false }">
                                    <button @click="expanded = !expanded" class="flex w-full items-center justify-between rounded-md px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                                        {{ $category->name }}
                                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="expanded" x-collapse class="pl-6 space-y-1 mt-1">
                                        @if(isset($subcategories[$category->id]))
                                            @foreach($subcategories[$category->id] as $subcategory)
                                                <a href="{{ route('customer.gigs.by.subcategory', $subcategory->slug) }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-gray-50">
                                                    {{ $subcategory->name }}
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 p-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-500 font-medium hover:text-red-700">Log out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Navigation (Categories with Icons) -->
    <div class="border-t border-gray-100 bg-white hidden md:block relative z-40 shadow-sm" x-data="{ activeCategory: null, timeout: null }">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex w-full gap-4 overflow-x-auto scrollbar-hide md:justify-between">
                    @foreach($categories as $category)
                        <div class="group relative px-3 py-3.5 border-b-[3px] border-transparent hover:border-emerald-500 transition-all duration-300 cursor-pointer"
                             @mouseenter="clearTimeout(timeout); activeCategory = {{ $category->id }}"
                             @mouseleave="timeout = setTimeout(() => activeCategory = null, 300)">
                            <a href="#" class="text-[15px] font-semibold text-gray-600 group-hover:text-emerald-600 whitespace-nowrap transition-colors block tracking-wide">
                                {{ $category->name }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mega Menu Dropdown -->
        <div class="absolute left-0 w-full bg-white shadow-[0_15px_50px_-10px_rgba(0,0,0,0.15)] border-t border-gray-50"
             x-show="activeCategory"
             @mouseenter="clearTimeout(timeout)"
             @mouseleave="timeout = setTimeout(() => activeCategory = null, 300)"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             style="display: none;"
             x-cloak>
            
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-[300px]">
                @foreach($categories as $category)
                    <div x-show="activeCategory == {{ $category->id }}" class="flex gap-12">
                        <!-- Left Side: Subcategories Grid -->
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                                <span class="bg-emerald-100 text-emerald-600 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </span>
                                Explore {{ $category->name }}
                            </h3>

                            <div class="grid grid-cols-4 gap-x-8 gap-y-6">
                                @if(isset($subcategories[$category->id]) && $subcategories[$category->id]->count() > 0)
                                    @foreach($subcategories[$category->id] as $index => $subcategory)
                                        <a href="{{ route('customer.gigs.by.subcategory', $subcategory->slug) }}" class="group/link flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover/link:bg-emerald-500 transition-colors"></span>
                                                <span class="text-gray-600 group-hover/link:text-emerald-700 font-medium text-[15px] transition-colors">
                                                    {{ $subcategory->name }}
                                                </span>
                                                @if($index === 0)
                                                    <span class="ml-1 text-[10px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wide">Top</span>
                                                @endif
                                                @if($index === 1)
                                                    <span class="ml-1 text-[10px] bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wide">New</span>
                                                @endif
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover/link:text-emerald-500 opacity-0 group-hover/link:opacity-100 transform -translate-x-2 group-hover/link:translate-x-0 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="col-span-full flex flex-col items-center justify-center text-gray-400 h-40 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                        </svg>
                                        <span class="text-sm font-medium">Coming Soon</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Side: Featured / Banner -->
                        <div class="w-80 hidden xl:block">
                            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 h-full text-white relative overflow-hidden group/card shadow-xl">
                                <!-- Decorative Circles -->
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl -ml-5 -mb-5"></div>
                                
                                <div class="relative z-10 flex flex-col h-full">
                                    <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/10 text-xs font-semibold tracking-wide uppercase mb-4 text-emerald-300 w-fit">
                                        Featured
                                    </span>
                                    
                                    <h4 class="text-2xl font-bold font-display mb-2 leading-tight">
                                        Hire top <br>
                                        <span class="text-emerald-400">{{ $category->name }}</span> <br>
                                        Talent
                                    </h4>
                                    
                                    <p class="text-gray-400 text-sm mb-8 leading-relaxed">
                                        Get professional results for your next project with our verified experts.
                                    </p>
                                    
                                    <button class="mt-auto w-full bg-white text-gray-900 font-bold py-3 rounded-xl hover:bg-emerald-50 transition-colors shadow-lg flex items-center justify-center gap-2 group-hover/card:scale-105 duration-300">
                                        Get Started
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Optional: Category Image if available -->
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay group-hover/card:scale-110 transition-transform duration-700" alt="">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-16">
        
        <!-- Banners Section -->
        @if(isset($banners) && $banners->count() > 0)
            <div class="relative w-full rounded-2xl overflow-hidden shadow-2xl" x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, timer: null }" x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000); $watch('activeSlide', () => { clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000) })">
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
                            <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover" alt="Banner">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                    @endforeach
                    <!-- Indicators -->
                    <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                        @foreach($banners as $index => $banner)
                            <button @click="activeSlide = {{ $index }}" :class="{'bg-white w-8': activeSlide === {{ $index }}, 'bg-white/50 w-2': activeSlide !== {{ $index }}}" class="h-2 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Default Hero Section (Fallback) -->
            <div class="relative rounded-2xl overflow-hidden bg-[#0d0d0d] text-white shadow-2xl min-h-[300px] flex items-center">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-900/30 rounded-full blur-[100px] -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-900/20 rounded-full blur-[80px] -ml-20 -mb-20"></div>
                
                <div class="relative z-10 w-full px-8 md:px-12 py-10 flex flex-col md:flex-row items-center justify-between">
                    <div class="max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/10 text-xs font-semibold tracking-wide uppercase mb-4 text-emerald-300">
                            Professional Dashboard
                        </span>
                        <h1 class="text-3xl md:text-5xl font-bold mb-4 font-sans tracking-tight leading-tight">
                            Find the perfect <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-200">freelance services</span>
                        </h1>
                        <p class="text-gray-400 text-lg mb-8 max-w-lg leading-relaxed">
                            Search for any service, any time, right here.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Categories (Circular Icons) -->
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Categories</h2>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full transition-colors">View All</a>
            </div>
            <div class="flex gap-8 overflow-x-auto pb-6 scrollbar-hide px-2">
                @foreach($categories as $category)
                    <a href="#" class="flex flex-col items-center gap-3 min-w-[80px] group cursor-pointer">
                        <div class="w-[72px] h-[72px] rounded-2xl bg-white shadow-[0_4px_20px_rgba(0,0,0,0.05)] border border-gray-100 flex items-center justify-center group-hover:shadow-[0_8px_25px_rgba(16,185,129,0.15)] group-hover:border-emerald-500/30 group-hover:-translate-y-1 transition-all duration-300">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-8 h-8 object-contain" alt="">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-700 group-hover:text-emerald-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-gray-600 group-hover:text-emerald-700 text-center transition-colors">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Popular Freelancers (Gigs) -->
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Popular Freelancers</h2>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full transition-colors">View All</a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide px-2 -mx-2">
                @foreach($popularGigs as $gig)
                    @include('Customer.components.gig-card-horizontal', ['gig' => $gig])
                @endforeach
                @if($popularGigs->isEmpty())
                     <div class="w-full text-center py-10 text-gray-400">No popular gigs found.</div>
                @endif
            </div>
        </div>

        <!-- Recently Viewed -->
        @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Recently Viewed</h2>
                <a href="#" class="text-sm font-semibold text-gray-500 hover:text-gray-900 bg-gray-50 px-3 py-1.5 rounded-full transition-colors">History</a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide px-2 -mx-2">
                @foreach($recentlyViewed as $gig)
                    @include('Customer.components.gig-card-horizontal', ['gig' => $gig])
                @endforeach
            </div>
        </div>
        @endif

        <!-- Single Promotional Banner -->
        @if(isset($singleBanner))
        <div class="relative rounded-2xl overflow-hidden shadow-xl group cursor-pointer h-[250px] md:h-[300px]">
            <img src="{{ $singleBanner['image'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $singleBanner['title'] }}">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent flex flex-col justify-center p-8 md:p-12">
                <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4 w-fit backdrop-blur-sm">
                    Recommended
                </span>
                <h3 class="text-3xl md:text-4xl font-bold text-white mb-2 font-display max-w-lg leading-tight">{{ $singleBanner['title'] }}</h3>
                <p class="text-gray-300 text-lg mb-8 max-w-md">{{ $singleBanner['subtitle'] }}</p>
                <button class="bg-white text-gray-900 font-bold py-3 px-8 rounded-xl hover:bg-emerald-50 transition-colors shadow-lg w-fit flex items-center gap-2 group/btn">
                    Explore Now
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Left/Right Banners -->
        @if(isset($promotionalBanners) && count($promotionalBanners) >= 2)
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($promotionalBanners as $banner)
            <div class="relative rounded-2xl overflow-hidden shadow-lg group cursor-pointer h-[200px]">
                <img src="{{ $banner['image'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $banner['title'] }}">
                <div class="absolute inset-0 bg-black/50 hover:bg-black/40 transition-colors flex flex-col justify-center p-8">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $banner['title'] }}</h3>
                    <p class="text-gray-200 font-medium">{{ $banner['subtitle'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Recently Saved -->
        @if(isset($recentlySaved) && $recentlySaved->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Recently Saved</h2>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full transition-colors">See All</a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide px-2 -mx-2">
                @foreach($recentlySaved as $gig)
                    @include('Customer.components.gig-card-horizontal', ['gig' => $gig])
                @endforeach
            </div>
        </div>
        @endif

        <!-- Spark Interest Section -->
        @if(isset($interests) && count($interests) > 0)
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">What sparks your interest?</h2>
                <a href="#" class="text-sm font-semibold text-gray-500 hover:text-gray-900 underline transition-colors">See All</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 px-2">
                @foreach($interests as $interest)
                <div class="group bg-white rounded-xl border border-gray-100 p-4 hover:border-emerald-500 hover:shadow-md transition-all duration-200 cursor-pointer flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-50 group-hover:bg-emerald-50 flex items-center justify-center text-gray-500 group-hover:text-emerald-600 transition-colors">
                        <!-- Icon Placeholder - In real app use dynamic icons -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-gray-700 group-hover:text-gray-900 text-sm">{{ $interest['name'] }}</span>
                    <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Referral Card -->
        <div class="px-2">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 md:p-10 relative overflow-hidden shadow-xl text-white">
                 <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                 <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/20 rounded-full blur-2xl -ml-10 -mb-10"></div>
                 
                 <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                     <div class="max-w-xl">
                         <h3 class="text-2xl md:text-3xl font-bold mb-3 font-display">Invite Friends & Get up to $100</h3>
                         <p class="text-indigo-100 text-lg mb-6">Introduce your friends to the easiest way to get things done.</p>
                         <button class="bg-white text-indigo-600 font-bold py-3 px-6 rounded-xl hover:bg-indigo-50 transition-colors shadow-lg">
                             Invite Friends
                         </button>
                     </div>
                     <div class="hidden md:block">
                         <img src="https://cdni.iconscout.com/illustration/premium/thumb/refer-a-friend-illustration-download-in-svg-png-gif-file-formats--referral-program-marketing-business-pack-illustrations-3665319.png" alt="Referral" class="w-64 h-auto drop-shadow-2xl">
                     </div>
                 </div>
            </div>
        </div>

        <!-- Testimonials -->
        @if(isset($testimonials) && count($testimonials) > 0)
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">What People Say</h2>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide px-2 -mx-2">
                @foreach($testimonials as $testimonial)
                <div class="min-w-[300px] w-[300px] bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="w-10 h-10 rounded-full">
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">{{ $testimonial['name'] }}</h4>
                            <p class="text-xs text-gray-500">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-3 text-sm">
                        @for($i = 0; $i < 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i < $testimonial['rating'] ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed italic">"{{ $testimonial['content'] }}"</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Trust & Safety Section -->
        <div class="bg-emerald-50/50 rounded-2xl p-8 md:p-12 border border-emerald-100">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 font-display">Trust & Safety</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-emerald-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Verified Freelancers</h3>
                                <p class="text-gray-500 leading-relaxed">Every freelancer on our platform is verified to ensure high-quality service delivery.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-emerald-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Secure Payments</h3>
                                <p class="text-gray-500 leading-relaxed">Your payment is held securely and only released when you approve the work.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-emerald-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">24/7 Support</h3>
                                <p class="text-gray-500 leading-relaxed">Our dedicated support team is here to help you around the clock.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="rounded-2xl shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500" alt="Trust and Safety">
                    <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-xl max-w-xs animate-bounce" style="animation-duration: 3s;">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex -space-x-2">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=1" alt="">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=2" alt="">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=3" alt="">
                            </div>
                            <span class="text-sm font-bold text-gray-900">10k+ Happy Clients</span>
                        </div>
                        <p class="text-xs text-gray-500">Join our community of satisfied customers.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspired by your history -->
        @if(isset($inspiredByHistory) && $inspiredByHistory->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Inspired by your history</h2>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full transition-colors">View All</a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide px-2 -mx-2">
                @foreach($inspiredByHistory as $gig)
                     @include('Customer.components.gig-card-horizontal', ['gig' => $gig])
                @endforeach
            </div>
        </div>
        @endif

        <!-- New Gigs -->
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">New Gigs</h2>
                <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full transition-colors">View All</a>
            </div>
            <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide px-2 -mx-2">
                @foreach($newGigs as $gig)
                     @include('Customer.components.gig-card-horizontal', ['gig' => $gig])
                @endforeach
            </div>
        </div>

    </main>
    
    <!-- Ultra Professional Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-1">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4">
                         <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-lg">f</div>
                         <span class="text-xl font-bold text-gray-900 tracking-tight font-display">findlancer</span>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Find the perfect freelance services for your business. Connect with talent, get work done.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Categories</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Graphics & Design</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Digital Marketing</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Writing & Translation</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Video & Animation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">About</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Press & News</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Partnerships</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Help & Support</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Trust & Safety</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Selling on Findlancer</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition-colors">Buying on Findlancer</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row items-center justify-between">
                <span class="text-gray-400 text-sm">© {{ date('Y') }} Findlancer International Ltd. All rights reserved.</span>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors"><span class="sr-only">Facebook</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a>
                    <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors"><span class="sr-only">Twitter</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg></a>
                    <a href="#" class="text-gray-400 hover:text-gray-900 transition-colors"><span class="sr-only">LinkedIn</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
