<header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-6 z-20 sticky top-0 transition-all duration-200">
    <div class="flex items-center gap-4 flex-1">
        <button class="md:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg hover:bg-slate-100 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <!-- Search Bar -->
        <div class="hidden md:flex items-center max-w-md w-full relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
            </div>
            <input type="text" 
                class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 sm:text-sm transition-all duration-200" 
                placeholder="Search gigs, orders, or messages...">
            <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                <kbd class="hidden sm:inline-block border border-slate-200 rounded px-2 text-[10px] font-medium text-slate-400 bg-white">⌘K</kbd>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Switch Mode -->
        <button class="hidden md:flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-primary-600 transition-colors bg-white px-4 py-2 rounded-full border border-slate-200 hover:border-primary-200 hover:shadow-md hover:shadow-primary-500/10 active:scale-95 duration-200">
            <span>Switch to Buying</span>
        </button>

        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

        <!-- Notifications -->
        <button class="p-2.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all relative group">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute top-2 right-2.5 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white group-hover:scale-110 transition-transform"></span>
        </button>

        <!-- Messages -->
        <a href="{{ route('provider.freelancer.chat.index') }}" class="p-2.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all relative group">
            <i class="fas fa-envelope text-lg"></i>
            <span class="absolute top-2 right-2 h-2.5 w-2.5 bg-blue-500 rounded-full border-2 border-white group-hover:scale-110 transition-transform"></span>
        </a>

        <!-- Mobile Profile Menu (visible only on small screens if sidebar is hidden, but usually sidebar covers it. Keeping for robustness) -->
        <div class="md:hidden">
             <!-- Placeholder for mobile menu toggle if needed -->
        </div>
    </div>
</header>