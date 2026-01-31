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
    @endif
    </nav>