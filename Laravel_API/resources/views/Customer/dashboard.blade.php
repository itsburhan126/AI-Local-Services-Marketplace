<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f7f7f7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Macondo&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        display: ['Macondo', 'cursive'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981', // Emerald 500
                            600: '#059669', // Emerald 600
                            700: '#047857', // Emerald 700
                            900: '#064e3b', // Emerald 900
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="h-full font-sans text-gray-700 antialiased" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation (Professional Marketplace Style) -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Left: Logo & Search -->
                <div class="flex items-center gap-8 flex-1">
                    <!-- Logo -->
                    <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center text-white font-bold text-xl">f</div>
                        <span class="text-2xl font-bold text-gray-900 tracking-tight font-display">findlancer</span>
                    </a>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden md:flex flex-1 max-w-2xl relative">
                        <input type="text" 
                               class="w-full pl-4 pr-12 py-2.5 border border-gray-300 rounded-[4px] focus:outline-none focus:border-black focus:ring-0 transition-colors placeholder-gray-500 text-sm" 
                               placeholder="What service are you looking for today?">
                        <button class="absolute right-0 top-0 h-full px-4 bg-black text-white rounded-r-[4px] hover:bg-gray-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right: Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#" class="text-sm font-semibold text-gray-500 hover:text-primary-600 transition-colors">Explore</a>
                    <a href="#" class="text-sm font-semibold text-gray-500 hover:text-primary-600 transition-colors">Orders</a>
                    <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">Switch to Selling</a>
                    
                    <!-- Icons -->
                    <div class="flex items-center space-x-4 border-l border-gray-200 pl-6 ml-2">
                        <button class="text-gray-400 hover:text-gray-600 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="flex items-center focus:outline-none">
                            <div class="h-9 w-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm border border-gray-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-500 border-2 border-white"></div>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Post a Request</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
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

        <!-- Secondary Navigation (Categories) -->
        <div class="border-t border-gray-200 bg-white shadow-sm hidden md:block">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3">
                    <div class="flex space-x-6 overflow-x-auto scrollbar-hide">
                        @forelse($categories as $category)
                            <a href="#" class="text-sm text-gray-600 hover:text-black hover:border-b-2 hover:border-black pb-0.5 transition-all whitespace-nowrap">{{ $category->name }}</a>
                        @empty
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Graphics & Design</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Digital Marketing</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Writing & Translation</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Video & Animation</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Music & Audio</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Programming & Tech</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-black transition-colors whitespace-nowrap">Business</a>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Banner -->
        <div class="bg-black text-white rounded-xl p-8 mb-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gray-800 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary-900 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -ml-16 -mb-16"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }} 👋</h1>
                    <p class="text-gray-300 mb-6 max-w-xl">Get offers from top freelancers for your project. It's simple and free to post a request.</p>
                    <button class="bg-primary-600 hover:bg-primary-500 text-white px-6 py-2.5 rounded font-semibold transition-colors shadow-lg shadow-primary-900/50">
                        Post a Request
                    </button>
                </div>
                <div class="mt-6 md:mt-0 hidden lg:block pr-12">
                     <!-- Illustration placeholder -->
                     <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                     </div>
                </div>
            </div>
        </div>

        <!-- Section: Continue Browsing / Recommended -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Recommended for you</h2>
                <a href="#" class="text-primary-600 font-semibold text-sm hover:underline">See All</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @forelse($recommendedGigs as $gig)
                    <div class="bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-300 flex flex-col h-full group cursor-pointer">
                        <!-- Thumbnail -->
                        <div class="relative h-44 overflow-hidden rounded-t-lg bg-gray-100">
                            @if($gig->thumbnail_image)
                                <img src="{{ asset('storage/' . $gig->thumbnail_image) }}" alt="{{ $gig->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-3 flex-1 flex flex-col">
                            <!-- Seller Info -->
                            <div class="flex items-center mb-2">
                                <img class="h-6 w-6 rounded-full object-cover" src="{{ $gig->provider->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($gig->provider->name).'&background=random' }}" alt="">
                                <div class="ml-2">
                                    <h4 class="text-sm font-bold text-gray-900 truncate max-w-[150px]">{{ $gig->provider->name }}</h4>
                                    <p class="text-[10px] text-gray-500">Level 2 Seller</p>
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="text-sm text-gray-700 hover:text-primary-600 transition-colors line-clamp-2 mb-2 leading-snug">
                                {{ $gig->title }}
                            </h3>

                            <!-- Rating -->
                            <div class="flex items-center text-xs mb-3">
                                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="font-bold text-gray-900 ml-1">5.0</span>
                                <span class="text-gray-400 ml-1">(42)</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-3 py-2 border-t border-gray-100 flex items-center justify-between bg-white rounded-b-lg">
                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <div class="flex items-center">
                                <span class="text-[10px] uppercase text-gray-400 font-semibold mr-1">Starting at</span>
                                <span class="text-lg font-bold text-gray-900">${{ $gig->packages->first()->price ?? '25' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                     <!-- Mock Data for when database is empty -->
                     @for($i = 0; $i < 5; $i++)
                        <div class="bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-300 flex flex-col h-full group cursor-pointer">
                            <div class="relative h-44 overflow-hidden rounded-t-lg bg-gray-200">
                                <img src="https://source.unsplash.com/random/400x300?tech,design&sig={{ $i }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="p-3 flex-1 flex flex-col">
                                <div class="flex items-center mb-2">
                                    <div class="h-6 w-6 rounded-full bg-gray-300"></div>
                                    <div class="ml-2">
                                        <div class="h-3 w-20 bg-gray-200 rounded"></div>
                                        <div class="h-2 w-12 bg-gray-100 rounded mt-1"></div>
                                    </div>
                                </div>
                                <div class="space-y-1 mb-2">
                                    <div class="h-4 w-full bg-gray-100 rounded"></div>
                                    <div class="h-4 w-2/3 bg-gray-100 rounded"></div>
                                </div>
                                <div class="flex items-center mt-auto">
                                    <div class="h-3 w-8 bg-gray-100 rounded"></div>
                                    <div class="h-3 w-6 bg-gray-100 rounded ml-1"></div>
                                </div>
                            </div>
                            <div class="px-3 py-2 border-t border-gray-100 flex items-center justify-between">
                                <div class="h-4 w-4 bg-gray-200 rounded-full"></div>
                                <div class="h-5 w-16 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                     @endfor
                @endforelse
            </div>
        </div>

        <!-- Section: Popular Professional Services -->
        <div class="mb-12">
             <h2 class="text-xl font-bold text-gray-900 mb-6">Popular professional services</h2>
             <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                 <!-- Service 1 -->
                 <a href="#" class="group relative rounded-xl overflow-hidden aspect-[3/4] hover:opacity-90 transition-opacity">
                     <img src="https://images.unsplash.com/photo-1599658880436-c61792e70672?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="absolute inset-0 w-full h-full object-cover" alt="Logo Design">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                     <div class="absolute top-3 left-3">
                        <span class="text-white font-bold text-sm">Logo Design</span>
                        <p class="text-gray-300 text-xs">Build your brand</p>
                     </div>
                 </a>
                 
                 <!-- Service 2 -->
                 <a href="#" class="group relative rounded-xl overflow-hidden aspect-[3/4] hover:opacity-90 transition-opacity">
                     <img src="https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="absolute inset-0 w-full h-full object-cover" alt="SEO">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                     <div class="absolute top-3 left-3">
                        <span class="text-white font-bold text-sm">SEO</span>
                        <p class="text-gray-300 text-xs">Unlock growth</p>
                     </div>
                 </a>

                 <!-- Service 3 -->
                 <a href="#" class="group relative rounded-xl overflow-hidden aspect-[3/4] hover:opacity-90 transition-opacity">
                     <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="absolute inset-0 w-full h-full object-cover" alt="Social Media">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                     <div class="absolute top-3 left-3">
                        <span class="text-white font-bold text-sm">Social Media</span>
                        <p class="text-gray-300 text-xs">Reach more</p>
                     </div>
                 </a>

                 <!-- Service 4 -->
                 <a href="#" class="group relative rounded-xl overflow-hidden aspect-[3/4] hover:opacity-90 transition-opacity">
                     <img src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="absolute inset-0 w-full h-full object-cover" alt="Voice Over">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                     <div class="absolute top-3 left-3">
                        <span class="text-white font-bold text-sm">Voice Over</span>
                        <p class="text-gray-300 text-xs">Share your message</p>
                     </div>
                 </a>

                  <!-- Service 5 -->
                  <a href="#" class="group relative rounded-xl overflow-hidden aspect-[3/4] hover:opacity-90 transition-opacity">
                     <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="absolute inset-0 w-full h-full object-cover" alt="Programming">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                     <div class="absolute top-3 left-3">
                        <span class="text-white font-bold text-sm">Wordpress</span>
                        <p class="text-gray-300 text-xs">Customize your site</p>
                     </div>
                 </a>
                 
                  <!-- Service 6 -->
                  <a href="#" class="group relative rounded-xl overflow-hidden aspect-[3/4] hover:opacity-90 transition-opacity">
                     <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" class="absolute inset-0 w-full h-full object-cover" alt="Data Entry">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                     <div class="absolute top-3 left-3">
                        <span class="text-white font-bold text-sm">Data Entry</span>
                        <p class="text-gray-300 text-xs">Learn your business</p>
                     </div>
                 </a>
             </div>
        </div>

    </main>
    
    <!-- Professional Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <span class="text-xl font-bold text-gray-900 font-display">findlancer</span>
                    <span class="text-gray-400 text-sm">© {{ date('Y') }} All rights reserved.</span>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-500 hover:text-gray-900"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a>
                    <a href="#" class="text-gray-500 hover:text-gray-900"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg></a>
                    <a href="#" class="text-gray-500 hover:text-gray-900"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.409-.06 3.809-.063zm1.318 1.045c-2.512 0-2.83.006-3.825.05-1.003.045-1.546.207-1.902.347a3.746 3.746 0 00-1.379.898 3.746 3.746 0 00-.898 1.379c-.14.356-.302.899-.347 1.902-.045.995-.05 1.313-.05 3.825v.52c0 2.512.006 2.83.05 3.825.045 1.003.207 1.546.347 1.902.245.629.572 1.141 1.277 1.442.356.14.899.302 1.902.347.995.045 1.313.05 3.825.05h.52c2.512 0 2.83-.006 3.825-.05 1.003-.045 1.546-.207 1.902-.347a3.746 3.746 0 001.379-.898 3.746 3.746 0 00.898-1.379c.14-.356.302-.899.347-1.902.045-.995.05-1.313.05-3.825v-.52c0-2.512-.006-2.83-.05-3.825-.045-1.003-.207-1.546-.347-1.902a3.746 3.746 0 00-.898-1.379 3.746 3.746 0 00-1.379-.898c-.356-.14-.899-.302-1.902-.347-.995-.045-1.313-.05-3.825-.05z" clip-rule="evenodd" /><path d="M12.001 5.956c-3.332 0-6.042 2.71-6.042 6.042 0 3.331 2.71 6.042 6.042 6.042 3.332 0 6.042-2.71 6.042-6.042 0-3.332-2.71-6.042-6.042-6.042zm0 10.635c-2.535 0-4.593-2.057-4.593-4.593 0-2.535 2.057-4.593 4.593-4.593 2.535 0 4.593 2.057 4.593 4.593 0 2.535-2.057 4.593-4.593 4.593zM17.404 8.21a1.072 1.072 0 11-2.144 0 1.072 1.072 0 012.144 0z" /></svg></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
