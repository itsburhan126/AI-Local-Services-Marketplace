<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Findlancer') | {{ config('app.name') }}</title>
    
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
                        'premium': '0 10px 40px -10px rgba(0,0,0,0.08)',
                    },
                    animation: {
                        'slide-in': 'slideIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'slide-out': 'slideOut 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': { transform: 'translateX(100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideOut: {
                            '0%': { transform: 'translateX(0)', opacity: '1' },
                            '100%': { transform: 'translateX(100%)', opacity: '0' },
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Toast Styles */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full font-body text-slate-600 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container space-y-4">
        @if (session('success'))
        <div class="toast bg-white border-l-4 border-green-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in">
            <div class="text-green-500">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Success</h4>
                <p class="text-sm text-gray-600">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif
        
        @if (session('error'))
        <div class="toast bg-white border-l-4 border-red-500 shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in">
            <div class="text-red-500">
                <i class="fas fa-exclamation-circle text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800">Error</h4>
                <p class="text-sm text-gray-600">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif
    </div>

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
                    
                    @auth
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
                    @else
                    <!-- Guest Actions -->
                    <div class="flex items-center space-x-4 border-l border-gray-200 pl-6">
                        <a href="{{ route('customer.login') }}" class="text-sm font-medium text-gray-700 hover:text-emerald-600 transition-colors">Sign In</a>
                        <a href="{{ route('customer.register') }}" class="text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg transition-colors shadow-sm hover:shadow-md">Join</a>
                    </div>
                    @endauth
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
                    
                    @if(isset($categories))
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
                                        @if(isset($subcategories) && isset($subcategories[$category->id]))
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
                    @endif
                </div>
                
                <div class="border-t border-gray-100 p-6">
                    @auth
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
                    @else
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('customer.login') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Sign In
                        </a>
                        <p class="text-center text-sm text-gray-500">
                            New here? <a href="{{ route('customer.register') }}" class="font-medium text-emerald-600 hover:text-emerald-500">Create an account</a>
                        </p>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Navigation (Categories with Icons) -->
    @if(isset($categories))
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
                this.$refs.scrollContainer.style.scrollBehavior = 'auto';
            },
            stop() {
                this.isDown = false;
                this.$refs.scrollContainer.classList.remove('cursor-grabbing');
                this.$refs.scrollContainer.classList.add('cursor-grab');
                this.$refs.scrollContainer.style.scrollBehavior = 'smooth';
            },
            move(e) {
                if (!this.isDown) return;
                e.preventDefault();
                const x = e.pageX - this.$refs.scrollContainer.offsetLeft;
                const walk = (x - this.startX) * 1.5;
                this.$refs.scrollContainer.scrollLeft = this.scrollPos - walk;
            }
         }">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative group/nav">
                <!-- Left Arrow -->
                <button @click="$refs.scrollContainer.scrollBy({ left: -320, behavior: 'smooth' })" 
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
                     class="flex w-full gap-8 overflow-x-auto scrollbar-hide items-center px-2 cursor-grab"
                     style="scroll-behavior: smooth;">
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
                <button @click="$refs.scrollContainer.scrollBy({ left: 320, behavior: 'smooth' })" 
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
                                @if(isset($subcategories) && isset($subcategories[$category->id]) && $subcategories[$category->id]->count() > 0)
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
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-12">
                <!-- Categories -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-base">Categories</h4>
                    <ul class="space-y-3">
                        @if(isset($categories))
                            @foreach($categories->take(6) as $category)
                                <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">{{ $category->name }}</a></li>
                            @endforeach
                        @else
                            <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Graphics & Design</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Digital Marketing</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Writing & Translation</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Video & Animation</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Music & Audio</a></li>
                            <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Programming & Tech</a></li>
                        @endif
                    </ul>
                </div>

                <!-- For Clients -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-base">For Clients</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">How Findlancer Works</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Customer Success Stories</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Trust & Safety</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Quality Guide</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Findlancer Learn</a></li>
                    </ul>
                </div>

                <!-- For Freelancers -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-base">For Freelancers</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('join.pro') }}" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Become a Findlancer</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Become an Agency</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Community Hub</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Forum</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Events</a></li>
                    </ul>
                </div>

                <!-- Business Solutions -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-base">Business Solutions</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Findlancer Pro</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Project Management</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">ClearVoice Content Marketing</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Working Not Working</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Contact Sales</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-bold text-gray-900 mb-4 text-base">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">About Findlancer</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Help & Support</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Social Impact</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Careers</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-500 hover:text-emerald-600 hover:underline transition-colors text-sm font-medium">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                     <div class="flex items-center gap-2">
                        <span class="font-bold text-2xl tracking-tight text-gray-900 font-display">findlancer<span class="text-emerald-600">.</span></span>
                    </div>
                    <p class="text-gray-400 text-sm">© {{ date('Y') }} Findlancer International Ltd.</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Social Icons -->
                    <div class="flex items-center gap-4">
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 p-2 rounded-full"><i class="fab fa-tiktok text-sm"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 p-2 rounded-full"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 p-2 rounded-full"><i class="fab fa-linkedin-in text-sm"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 p-2 rounded-full"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 p-2 rounded-full"><i class="fab fa-pinterest text-sm"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 p-2 rounded-full"><i class="fab fa-twitter text-sm"></i></a>
                    </div>
                    
                    <!-- Settings -->
                    <div class="flex items-center gap-4 ml-2">
                        <button class="flex items-center gap-1.5 text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors">
                            <i class="fas fa-globe text-gray-400"></i>
                            <span>English</span>
                        </button>
                        <button class="flex items-center gap-1.5 text-gray-500 hover:text-gray-900 font-medium text-sm transition-colors">
                            <span class="text-gray-400 font-bold">$</span>
                            <span>USD</span>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all">
                            <i class="fas fa-universal-access"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')
    <script>
        // Auto-dismiss toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.replace('animate-slide-in', 'animate-slide-out');
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }, 5000);
            });
        });

        // Global Toast Function
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Define colors and icons based on type
            let borderColor, iconColor, icon, title;
            if (type === 'success') {
                borderColor = 'border-green-500';
                iconColor = 'text-green-500';
                icon = 'fa-check-circle';
                title = 'Success';
            } else if (type === 'error') {
                borderColor = 'border-red-500';
                iconColor = 'text-red-500';
                icon = 'fa-exclamation-circle';
                title = 'Error';
            } else if (type === 'info') {
                borderColor = 'border-blue-500';
                iconColor = 'text-blue-500';
                icon = 'fa-info-circle';
                title = 'Info';
            }
            
            toast.className = `toast bg-white border-l-4 ${borderColor} shadow-premium rounded-r-lg p-4 flex items-center gap-3 min-w-[300px] transform translate-x-full animate-slide-in`;
            toast.innerHTML = `
                <div class="${iconColor}">
                    <i class="fas ${icon} text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">${title}</h4>
                    <p class="text-sm text-gray-600">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Auto remove
            setTimeout(() => {
                toast.classList.replace('animate-slide-in', 'animate-slide-out');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 5000);
        };
    </script>
</body>
</html>