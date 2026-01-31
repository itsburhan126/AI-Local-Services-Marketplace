<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $gig->title }} | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['"Inter"', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
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
    <div class="border-t border-gray-100 bg-white hidden md:block relative z-40 shadow-sm" 
         x-data="{ 
            activeCategory: null, 
            timeout: null,
            isDown: false,
            startX: 0,
            scrollPos: 0,
            start(e) {
                this.isDown = true;
                this.startX = e.pageX - this.$refs.scrollContainer.offsetLeft;
                this.scrollPos = this.$refs.scrollContainer.scrollLeft;
                this.$refs.scrollContainer.classList.add('cursor-grabbing');
                this.$refs.scrollContainer.classList.remove('cursor-grab');
            },
            stop() {
                this.isDown = false;
                this.$refs.scrollContainer.classList.remove('cursor-grabbing');
                this.$refs.scrollContainer.classList.add('cursor-grab');
            },
            move(e) {
                if (!this.isDown) return;
                e.preventDefault();
                const x = e.pageX - this.$refs.scrollContainer.offsetLeft;
                const walk = (x - this.startX) * 2;
                this.$refs.scrollContainer.scrollLeft = this.scrollPos - walk;
            }
         }">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative group/nav">
                <!-- Left Arrow -->
                <button @click="$refs.scrollContainer.scrollBy({ left: -300, behavior: 'smooth' })" 
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 p-2 bg-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] rounded-full text-gray-600 hover:text-black hover:scale-110 transition-all opacity-0 group-hover/nav:opacity-100 focus:opacity-100 border border-gray-100 -ml-4"
                        aria-label="Scroll left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Categories Scroll Container -->
                <div x-ref="scrollContainer" 
                     @mousedown="start"
                     @mouseleave="stop"
                     @mouseup="stop"
                     @mousemove="move"
                     class="flex w-full gap-8 overflow-x-auto scrollbar-hide items-center px-2 cursor-grab">
                    @foreach($categories as $category)
                        <div class="group relative px-1 py-3.5 border-b-[3px] border-transparent hover:border-emerald-500 transition-all duration-300 cursor-pointer flex-shrink-0"
                             @mouseenter="clearTimeout(timeout); activeCategory = {{ $category->id }}"
                             @mouseleave="timeout = setTimeout(() => activeCategory = null, 300)">
                            <a href="#" class="text-[15px] font-semibold text-gray-600 group-hover:text-emerald-600 whitespace-nowrap transition-colors block tracking-wide">
                                {{ $category->name }}
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Right Arrow -->
                <button @click="$refs.scrollContainer.scrollBy({ left: 300, behavior: 'smooth' })" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 p-2 bg-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] rounded-full text-gray-600 hover:text-black hover:scale-110 transition-all opacity-0 group-hover/nav:opacity-100 focus:opacity-100 border border-gray-100 -mr-4"
                        aria-label="Scroll right">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
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
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover/link:text-emerald-500 opacity-0 group-hover/link:opacity-100 transition-all duration-200 transform -translate-x-2 group-hover/link:translate-x-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="col-span-4 text-gray-400 italic">No subcategories found.</div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Side: Featured/Promo -->
                        <div class="w-80 border-l border-gray-100 pl-12 hidden xl:block">
                            <div class="bg-gray-50 rounded-2xl p-6 text-center">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-3xl">🚀</div>
                                <h4 class="font-bold text-gray-900 mb-2">Grow with {{ $category->name }}</h4>
                                <p class="text-sm text-gray-500 mb-6">Find top-rated experts to help you scale your business.</p>
                                <button class="w-full bg-black text-white font-bold py-2.5 rounded-lg hover:bg-gray-800 transition-colors">
                                    Browse All
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </nav>

    <!-- Main Content: Gig Details -->
    <main class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activePackage: 0 }">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-emerald-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="#" class="ml-1 text-gray-600 hover:text-emerald-600 md:ml-2 transition-colors">{{ $gig->category->name ?? 'Category' }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-400 md:ml-2 truncate max-w-xs">{{ $gig->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Gig Info (8 cols) -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Header Info -->
                <div class="space-y-4">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight font-display">{{ $gig->title }}</h1>
                    
                    <div class="flex items-center gap-4">
                        @php
                            $providerName = $gig->provider ? $gig->provider->name : 'Provider';
                            $providerImage = 'https://ui-avatars.com/api/?name=' . urlencode($providerName) . '&background=random';
                            
                            if ($gig->provider && !empty($gig->provider->image)) {
                                $pPath = $gig->provider->image;
                                if (filter_var($pPath, FILTER_VALIDATE_URL)) {
                                    $pParsed = parse_url($pPath, PHP_URL_PATH);
                                    $pRelative = preg_replace('/^\/?storage\//', '', ltrim($pParsed, '/'));
                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($pRelative)) {
                                        $providerImage = $pPath;
                                    }
                                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($pPath)) {
                                    $providerImage = asset('storage/' . $pPath);
                                }
                            }
                        @endphp
                        <img src="{{ $providerImage }}" 
                             alt="Seller" 
                             class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm ring-2 ring-gray-100">
                        <div>
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                {{ $gig->provider->name ?? 'Unknown Seller' }}
                                <span class="text-xs font-normal text-gray-500">| Level 2 Seller</span> <!-- Dynamic Level if available -->
                            </h3>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="flex items-center text-yellow-400 gap-0.5">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 {{ $i < round($gig->rating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                    <span class="font-bold text-gray-900 ml-1">{{ number_format($gig->rating, 1) }}</span>
                                </div>
                                <span class="text-gray-400">({{ $gig->reviews_count ?? 0 }} reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section -->
                @php
                    $defaultImage = 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=1200&auto=format&fit=crop';
                    $mainImage = $defaultImage;
                    
                    // Helper function to resolve image path
                    $resolveImage = function($path) {
                        if (empty($path)) return null;
                        
                        // Check if it's a full URL
                        if (filter_var($path, FILTER_VALIDATE_URL)) {
                            // Extract relative path for checking existence
                            $parsedPath = parse_url($path, PHP_URL_PATH); // e.g., /storage/gigs/thumbnails/...
                            $relativePath = preg_replace('/^\/?storage\//', '', ltrim($parsedPath, '/'));
                            
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                                return $path;
                            }
                        } else {
                            // It's a relative path
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                                return asset('storage/' . $path);
                            }
                        }
                        return null;
                    };

                    // Resolve Main Image
                    $resolvedThumbnail = $resolveImage($gig->thumbnail_image);
                    $resolvedImage = $resolveImage($gig->image);
                    
                    if ($resolvedThumbnail) {
                        $mainImage = $resolvedThumbnail;
                    } elseif ($resolvedImage) {
                        $mainImage = $resolvedImage;
                    }
                    
                    $galleryImages = [];
                    // Add main image first
                    $galleryImages[] = $mainImage;

                    if (!empty($gig->images) && is_array($gig->images)) {
                        foreach($gig->images as $img) {
                            $resolved = $resolveImage($img);
                            if ($resolved) {
                                $galleryImages[] = $resolved;
                            }
                        }
                    }
                    
                    // Unique images only
                    $galleryImages = array_unique($galleryImages);
                @endphp

                <div class="space-y-4" x-data="{ 
                    activeImage: '{{ $mainImage }}',
                    images: {{ json_encode(array_values($galleryImages)) }}
                }">
                    <!-- Main Image -->
                    <div class="rounded-2xl overflow-hidden border border-gray-100 bg-gray-50 aspect-video relative group cursor-zoom-in shadow-sm">
                        <img :src="activeImage" 
                             alt="{{ $gig->title }}" 
                             class="w-full h-full object-cover transition-all duration-300">
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="activeImage = img" 
                                    class="relative w-24 h-16 rounded-lg overflow-hidden flex-shrink-0 border-2 transition-all"
                                    :class="activeImage === img ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-transparent opacity-70 hover:opacity-100'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 font-display">About This Gig</h2>
                    <div class="prose prose-slate prose-lg max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($gig->description)) !!}
                    </div>
                </div>

                <!-- About The Seller -->
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 font-display">About The Seller</h2>
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="flex flex-col items-center gap-4 text-center min-w-[150px]">
                            <a href="{{ route('customer.seller.profile', \Illuminate\Support\Str::slug($gig->provider->name ?? 'provider')) }}" class="group block">
                                <div class="relative inline-block">
                                    <img src="{{ $providerImage }}" 
                                         alt="Seller" 
                                         class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-md group-hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="mt-4">
                                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $gig->provider->name ?? 'Unknown' }}</h3>
                                    <p class="text-sm text-gray-500">{{ $gig->provider->headline ?? $gig->provider->providerProfile->company_name ?? 'Professional Freelancer' }}</p>
                                </div>
                            </a>
                        </div>
                        <div class="flex-1 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <span class="text-gray-400 block mb-1">From</span>
                                    <span class="font-semibold text-gray-900">{{ $gig->provider->providerProfile->country ?? 'Global' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block mb-1">Member since</span>
                                    <span class="font-semibold text-gray-900">{{ $gig->provider->created_at->format('M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block mb-1">Avg. Response Time</span>
                                    <span class="font-semibold text-gray-900">1 Hour</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block mb-1">Last Delivery</span>
                                    <span class="font-semibold text-gray-900">{{ $lastDelivery }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $gig->provider->providerProfile->about ?? 'Hello! I am a passionate freelancer ready to help you with your projects. I have years of experience and I am committed to delivering high-quality work.' }}
                            </p>
                            <a href="{{ route('customer.seller.profile', \Illuminate\Support\Str::slug($gig->provider->name ?? 'provider')) }}" class="inline-block text-emerald-600 font-bold text-sm border border-emerald-600 px-6 py-2 rounded-lg hover:bg-emerald-50 transition-colors">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="pt-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 font-display">Reviews</h2>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 text-lg">{{ number_format($gig->rating, 1) }}</span>
                            <div class="flex text-yellow-400">
                                @for($i=0; $i<5; $i++)
                                    <svg class="w-5 h-5 {{ $i < round($gig->rating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-gray-500">({{ $gig->reviews_count }} reviews)</span>
                        </div>
                    </div>
                    
                    @if($gig->reviews && $gig->reviews->count() > 0)
                        <div class="space-y-8">
                            @foreach($gig->reviews as $review)
                                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $review->user->image ? asset('storage/' . $review->user->image) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" class="w-12 h-12 rounded-full border border-gray-100" alt="">
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
                        <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                            <p class="text-gray-500 font-medium">No reviews yet. Be the first to review!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Pricing & Booking (4 cols, Sticky) -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 space-y-8">
                    <!-- Packages Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-[0_8px_30px_rgba(0,0,0,0.04)] overflow-hidden sticky top-28">
                        <!-- Tabs -->
                        <div class="grid grid-cols-3 border-b border-gray-100">
                            @foreach($gig->packages as $index => $package)
                                <button @click="activePackage = {{ $index }}" 
                                        class="py-4 text-xs sm:text-sm font-bold transition-all uppercase tracking-wide relative"
                                        :class="activePackage === {{ $index }} ? 'text-emerald-600 bg-emerald-50/30' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'">
                                    {{ $package->name ?? 'Pkg ' . ($index + 1) }}
                                    <div x-show="activePackage === {{ $index }}" class="absolute bottom-0 left-0 w-full h-0.5 bg-emerald-600" layoutId="underline"></div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Content -->
                        @foreach($gig->packages as $index => $package)
                            <div x-show="activePackage === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="p-6 md:p-8 flex flex-col h-full">
                                
                                <div class="flex items-start justify-between mb-6">
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight max-w-[70%]">{{ $package->title ?? $package->name }}</h3>
                                    <div class="text-right">
                                        <span class="text-3xl font-extrabold text-gray-900 tracking-tight">${{ $package->price }}</span>
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-600 leading-relaxed mb-6 min-h-[60px]">{{ $package->description }}</p>

                                <div class="space-y-4 mb-8">
                                    <div class="flex items-center gap-4 text-sm font-semibold text-gray-800">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ $package->delivery_time }} Days Delivery
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            {{ $package->revisions }} Revisions
                                        </div>
                                    </div>
                                    
                                    <!-- Features List -->
                                    <ul class="space-y-3">
                                        <li class="flex items-start gap-3 text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            <span>Source File Included</span>
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            <span>High Resolution</span>
                                        </li>
                                        <li class="flex items-start gap-3 text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            <span>Commercial Use</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-auto space-y-3">
                                    <a href="{{ route('customer.gigs.checkout', ['slug' => $gig->slug, 'package_id' => $package->id]) }}" 
                                       class="w-full bg-black text-white font-bold py-3.5 rounded-lg hover:bg-gray-800 transition-all flex items-center justify-center gap-2 group shadow-lg shadow-gray-200">
                                        <span>Continue</span>
                                        <span class="opacity-0 group-hover:opacity-100 transition-opacity">→</span>
                                    </a>
                                    
                                    <button @click="compareOpen = true" class="w-full py-2.5 text-center text-emerald-600 text-sm font-semibold hover:bg-emerald-50 rounded-lg transition-colors">
                                        Compare Packages
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Contact Seller -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 text-center shadow-sm">
                        <a href="{{ route('customer.chat.index', ['user_id' => $gig->provider_id]) }}" class="w-full bg-white border-2 border-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:border-black hover:text-black transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            Contact Seller
                        </a>
                    </div>
                    
                    <!-- Safety Badge -->
                    <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        SSL Secure Payment
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Gigs -->
        @if($relatedGigs->count() > 0)
        <div class="mt-24 border-t border-gray-100 pt-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 font-display">More Services Like This</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($relatedGigs as $relatedGig)
                    @include('Customer.components.gig-card', ['gig' => $relatedGig])
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <!-- Compare Packages Modal -->
    <div x-show="compareOpen" 
         style="display: none;"
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="compareOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="compareOpen = false"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="compareOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold leading-6 text-gray-900" id="modal-title">Compare Packages</h3>
                                <button @click="compareOpen = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-gray-200 rounded-xl overflow-hidden divide-y md:divide-y-0 md:divide-x divide-gray-200">
                                @foreach($gig->packages as $package)
                                    <div class="p-6 hover:bg-gray-50 transition-colors">
                                        <div class="mb-6">
                                            <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $package->name }}</h4>
                                            <p class="text-sm text-gray-500 min-h-[40px]">{{ $package->description }}</p>
                                        </div>
                                        
                                        <div class="mb-6">
                                            <span class="text-3xl font-extrabold text-gray-900">${{ $package->price }}</span>
                                        </div>

                                        <div class="space-y-4 mb-8">
                                            <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $package->delivery_time }} Days Delivery
                                            </div>
                                            <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                {{ $package->revisions }} Revisions
                                            </div>
                                            
                                            <div class="border-t border-gray-100 pt-4 space-y-3">
                                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    <span>Source File</span>
                                                </div>
                                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    <span>Commercial Use</span>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('customer.gigs.checkout', ['slug' => $gig->slug, 'package_id' => $package->id]) }}" 
                                           class="block w-full bg-black text-white font-bold py-3 rounded-lg hover:bg-gray-800 transition-colors text-center">
                                            Select
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
