<aside class="w-64 bg-white border-r border-slate-100 flex flex-col fixed left-0 top-0 h-full z-30 transition-transform duration-300 md:translate-x-0 -translate-x-full" id="sidebar">
    <!-- Brand -->
    <div class="h-16 flex items-center px-6 border-b border-slate-50">
        <a href="{{ route('landing') }}" class="flex items-center gap-2">
            <div class="h-8 w-8 bg-gradient-to-br from-primary-600 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary-500/30">
                P
            </div>
            <span class="text-xl font-bold text-slate-900 tracking-tight">Pro<span class="text-primary-600">Market</span></span>
        </a>
    </div>
    
    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6 scrollbar-thin scrollbar-thumb-slate-200">
        
        <!-- Main Section -->
        <div>
            <h3 class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Main</h3>
            <nav class="space-y-1">
                <a href="{{ route('provider.freelancer.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('provider.freelancer.dashboard') ? 'bg-primary-50 text-primary-600 shadow-sm shadow-primary-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-home w-5 text-center mr-3 {{ request()->routeIs('provider.freelancer.dashboard') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Dashboard
                </a>
                <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                    <i class="fas fa-inbox w-5 text-center mr-3 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    Inbox
                    <span class="ml-auto bg-primary-100 text-primary-600 py-0.5 px-2 rounded-full text-xs font-bold">3</span>
                </a>
            </nav>
        </div>

        <!-- Selling Section -->
        <div>
            <h3 class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Selling</h3>
            <nav class="space-y-1">
                <a href="{{ route('provider.freelancer.gigs.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('provider.freelancer.gigs.*') ? 'bg-primary-50 text-primary-600 shadow-sm shadow-primary-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-briefcase w-5 text-center mr-3 {{ request()->routeIs('provider.freelancer.gigs.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    My Gigs
                </a>
                <a href="{{ route('provider.freelancer.orders.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('provider.freelancer.orders.*') ? 'bg-primary-50 text-primary-600 shadow-sm shadow-primary-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fas fa-box-open w-5 text-center mr-3 {{ request()->routeIs('provider.freelancer.orders.*') ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors"></i>
                    Orders
                </a>
                <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                    <i class="fas fa-chart-line w-5 text-center mr-3 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    Analytics
                </a>
                <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                    <i class="fas fa-wallet w-5 text-center mr-3 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    Earnings
                </a>
            </nav>
        </div>

        <!-- Account Section -->
        <div>
            <h3 class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Account</h3>
            <nav class="space-y-1">
                <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                    <i class="fas fa-user w-5 text-center mr-3 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    Profile
                </a>
                <a href="#" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                    <i class="fas fa-cog w-5 text-center mr-3 text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    Settings
                </a>
            </nav>
        </div>
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
            <div class="relative">
                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-primary-100 to-primary-50 flex items-center justify-center text-primary-600 font-bold text-sm border border-primary-100">
                    {{ substr(Auth::guard('web')->user()->name ?? 'U', 0, 1) }}
                </div>
                <span class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate group-hover:text-primary-600 transition-colors">{{ Auth::guard('web')->user()->name ?? 'User' }}</p>
                <p class="text-xs text-slate-500 truncate">Online</p>
            </div>
            <form action="{{ route('provider.logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>