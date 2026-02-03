<header class="bg-white border-b border-slate-200 sticky top-0 z-50">
    <!-- Top Row: Logo, Search, Notifications, Profile -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Logo & Search -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="h-8 w-8 bg-gradient-to-br from-primary-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-500/30">
                        P
                    </div>
                    <span class="text-xl font-bold text-slate-900 tracking-tight">Pro<span class="text-primary-600">Market</span></span>
                </a>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:flex items-center max-w-md w-96 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" 
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-500 focus:border-slate-500 sm:text-sm transition-all duration-200" 
                        placeholder="Search...">
                    <button class="absolute inset-y-0 right-0 px-4 py-2 bg-slate-800 text-white rounded-r-md text-sm font-medium hover:bg-slate-700 transition-colors">
                        Search
                    </button>
                </div>
            </div>

            <!-- Right Side: Navigation & Actions -->
            <div class="flex items-center gap-4 sm:gap-6">
                <!-- Notifications -->
                <button class="text-slate-500 hover:text-slate-700 relative group transition-colors">
                    <i class="far fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
                </button>

                <!-- Messages -->
                <a href="{{ route('provider.freelancer.chat.index') }}" class="text-slate-500 hover:text-slate-700 relative group transition-colors">
                    <i class="far fa-envelope text-lg"></i>
                    <span class="absolute -top-1 -right-1 h-2 w-2 bg-blue-500 rounded-full border border-white"></span>
                </a>

                <!-- Help -->
                <button class="text-slate-500 hover:text-slate-700 transition-colors hidden sm:block">
                    <i class="far fa-question-circle text-lg"></i>
                </button>

                <!-- Orders (Direct Link for Quick Access) -->
                <a href="{{ route('provider.freelancer.orders.index') }}" class="text-slate-600 font-medium text-sm hover:text-primary-600 transition-colors hidden md:block">Orders</a>

                <!-- Switch Mode -->
                <button class="hidden lg:flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    <span>Switch to Buying</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative ml-2" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-slate-200 overflow-hidden border border-slate-300">
                            <img src="{{ Auth::guard('web')->user()->profile_photo_url }}" alt="Profile" class="h-full w-full object-cover">
                        </div>
                        <span class="hidden md:block h-2.5 w-2.5 bg-green-500 rounded-full border-2 border-white absolute bottom-0 right-0"></span>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50 origin-top-right" style="display: none;">
                        
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm text-slate-500">Signed in as</p>
                            <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::guard('web')->user()->name ?? 'User' }}</p>
                        </div>
                        
                        <a href="{{ route('provider.freelancer.profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                        <a href="{{ route('provider.freelancer.settings') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Settings</a>
                        <a href="{{ route('provider.freelancer.payout.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Billing</a>
                        <a href="{{ route('provider.freelancer.verification.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Verification</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">English <span class="float-right text-slate-400">🌐</span></a>
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">USD <span class="float-right text-slate-400">$</span></a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('provider.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Navigation Menu -->
    <div class="border-t border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-x-8 overflow-visible" aria-label="Global">
                <a href="{{ route('provider.freelancer.dashboard') }}" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('provider.freelancer.dashboard') ? 'border-primary-500 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    Dashboard
                </a>

                <!-- My Business Dropdown Group -->
                <div class="relative py-3" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="inline-flex items-center text-sm font-medium border-b-2 border-transparent {{ request()->routeIs('provider.freelancer.gigs.*') || request()->routeIs('provider.freelancer.orders.*') ? 'text-primary-600' : 'text-slate-500 hover:text-slate-700' }} focus:outline-none">
                        <span>My Business</span>
                        <i class="fas fa-chevron-down ml-2 h-3 w-3 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    
                    <div x-show="open"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 z-50 mt-1 w-48 rounded-md shadow-lg bg-white border border-slate-100 focus:outline-none">
                        <div class="py-2">
                            <a href="{{ route('provider.freelancer.orders.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Orders</a>
                            <a href="{{ route('provider.freelancer.gigs.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Gigs</a>
                            <a href="{{ route('provider.freelancer.profile') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Profile</a>
                            <a href="{{ route('provider.freelancer.earnings') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Earnings</a>
                        </div>
                    </div>
                </div>

                <!-- Growth & Marketing -->
                <div class="relative py-3" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="inline-flex items-center text-sm font-medium border-b-2 border-transparent {{ request()->routeIs('provider.freelancer.marketing') ? 'text-primary-600 border-primary-500' : 'text-slate-500 hover:text-slate-700' }} focus:outline-none">
                        <span>Growth & Marketing</span>
                        <i class="fas fa-chevron-down ml-2 h-3 w-3 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 z-50 mt-1 w-56 rounded-md shadow-lg bg-white border border-slate-100 focus:outline-none">
                        <div class="py-2">
                            <a href="{{ route('provider.freelancer.marketing') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Scale Your Business</a>
                            <a href="{{ route('provider.freelancer.marketing') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Contacts</a>
                        </div>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="relative py-3" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="inline-flex items-center text-sm font-medium border-b-2 border-transparent {{ request()->routeIs('provider.freelancer.analytics') ? 'text-primary-600 border-primary-500' : 'text-slate-500 hover:text-slate-700' }} focus:outline-none">
                        <span>Analytics</span>
                        <i class="fas fa-chevron-down ml-2 h-3 w-3 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 z-50 mt-1 w-48 rounded-md shadow-lg bg-white border border-slate-100 focus:outline-none">
                        <div class="py-2">
                            <a href="{{ route('provider.freelancer.analytics') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Overview</a>
                            <a href="{{ route('provider.freelancer.analytics') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors">Repeat Business</a>
                        </div>
                    </div>
                </div>

                <!-- Verification -->
                <a href="{{ route('provider.freelancer.verification.index') }}" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('provider.freelancer.verification.index') ? 'border-primary-500 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                    Verification
                </a>

            </nav>
        </div>
    </div>
</header>

<!-- Mobile Menu (Overlay) -->
<div x-data="{ open: false }" class="md:hidden">
    <!-- This part can be implemented if needed, relying on a hamburger menu in the top row if screen is small -->
</div>
