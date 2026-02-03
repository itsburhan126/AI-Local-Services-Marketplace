<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - {{ \App\Models\Setting::get('app_name', config('app.name')) }}</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            
            --color-primary-50: #eef2ff;
            --color-primary-100: #e0e7ff;
            --color-primary-200: #c7d2fe;
            --color-primary-300: #a5b4fc;
            --color-primary-400: #818cf8;
            --color-primary-500: #6366f1;
            --color-primary-600: #4f46e5;
            --color-primary-700: #4338ca;
            --color-primary-800: #3730a3;
            --color-primary-900: #312e81;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f4f6; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-gradient {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
        }

        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            margin-bottom: 4px;
        }

        .nav-item-active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0.05) 100%);
            color: #6366f1;
            font-weight: 600;
            border: 1px dashed rgba(99, 102, 241, 0.3);
        }
        
        .nav-item:hover:not(.nav-item-active) {
            background: rgba(255, 255, 255, 0.03);
            color: #f8fafc;
            transform: translateX(4px);
        }

        /* Premium Card Styles */
        .premium-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        
        .content-transition {
            animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashed-border {
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='16' ry='16' stroke='%236366F140' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            border-radius: 16px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">
    @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->hasRole('demo-admin'))
    <div class="bg-amber-500 text-white text-xs font-bold text-center py-1 fixed top-0 left-0 right-0 z-50">
        <i class="fas fa-exclamation-triangle mr-1"></i> DEMO MODE: View Only - Changes will not be saved.
    </div>
    @endif
    
    <div id="mobile-overlay" class="fixed inset-0 bg-slate-900/50 z-20 hidden transition-opacity opacity-0 md:hidden backdrop-blur-sm"></div>
    
    <div class="flex h-screen overflow-hidden {{ (Auth::guard('admin')->check() && Auth::guard('admin')->user()->hasRole('demo-admin')) ? 'pt-6' : '' }}">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-72 sidebar-gradient flex-shrink-0 flex flex-col z-30 shadow-2xl transition-all duration-300 fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 border-r border-white/10">
            <!-- Brand -->
            <div class="h-24 flex items-center px-8 border-b border-white/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 text-white relative overflow-hidden group">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <i class="fas fa-layer-group text-xl relative z-10"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-xl text-white tracking-tight leading-none mb-1">{{ \App\Models\Setting::get('app_name', config('app.name')) }}</h1>
                        <p class="text-[10px] font-semibold text-indigo-300 uppercase tracking-widest">Admin Console</p>
                    </div>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button id="mobile-menu-close" class="md:hidden ml-auto text-slate-400 hover:text-white transition-colors p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <div id="sidebar-nav" class="flex-1 overflow-y-auto py-8 px-4 space-y-2">
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Dashboard</p>
                
                @if(Auth::guard('admin')->user()->hasPermission('dashboard_access'))
                <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-home w-6 text-lg {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Overview</span>
                </a>
                @endif

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Service Management</p>
                
                <a href="{{ route('admin.categories.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.categories.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-layer-group w-6 text-lg {{ request()->routeIs('admin.categories.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.services.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.services.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-concierge-bell w-6 text-lg {{ request()->routeIs('admin.services.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Services</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Freelancer Management</p>

                <a href="{{ route('admin.freelancers.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.freelancers.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-user-tie w-6 text-lg {{ request()->routeIs('admin.freelancers.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Freelancers</span>
                </a>

                <a href="{{ route('admin.kyc.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.kyc.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-id-card w-6 text-lg {{ request()->routeIs('admin.kyc.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>KYC Requests</span>
                </a>

                <a href="{{ route('admin.service-types.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.service-types.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-tags w-6 text-lg {{ request()->routeIs('admin.service-types.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Service Types</span>
                </a>

                <a href="{{ route('admin.zones.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.zones.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-map-marked-alt w-6 text-lg {{ request()->routeIs('admin.zones.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Service Zones</span>
                </a>

                <a href="{{ route('admin.interests.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.interests.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-bolt w-6 text-lg {{ request()->routeIs('admin.interests.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Spark Interest</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Booking Management</p>

                <a href="{{ route('admin.bookings.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.bookings.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-calendar-check w-6 text-lg {{ request()->routeIs('admin.bookings.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Bookings</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Provider Center</p>

                <a href="{{ route('admin.providers.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.providers.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-users-cog w-6 text-lg {{ request()->routeIs('admin.providers.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Providers</span>
                </a>

                <a href="{{ route('admin.subscription-plans.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.subscription-plans.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-crown w-6 text-lg {{ request()->routeIs('admin.subscription-plans.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Subscription Plans</span>
                </a>

                <a href="{{ route('admin.withdrawals.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.withdrawals.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-money-check-alt w-6 text-lg {{ request()->routeIs('admin.withdrawals.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Withdrawals</span>
                </a>

                <a href="{{ route('admin.payout-methods.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.payout-methods.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-wallet w-6 text-lg {{ request()->routeIs('admin.payout-methods.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Payout Methods</span>
                </a>



                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Marketing & Tools</p>
                
                <a href="{{ route('admin.chat.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.chat.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-comments w-6 text-lg {{ request()->routeIs('admin.chat.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Messages</span>
                    <span class="ml-auto bg-indigo-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">LIVE</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.coupons.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-ticket-alt w-6 text-lg {{ request()->routeIs('admin.coupons.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Coupons</span>
                </a>

                <a href="{{ route('admin.promotions.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.promotions.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-bullhorn w-6 text-lg {{ request()->routeIs('admin.promotions.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Promotions</span>
                </a>

                <a href="{{ route('admin.banners.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.banners.index') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-images w-6 text-lg {{ request()->routeIs('admin.banners.index') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>App Banners</span>
                </a>

                <a href="{{ route('admin.banners.single-promo') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.banners.single-promo') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-ad w-6 text-lg {{ request()->routeIs('admin.banners.single-promo') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Single Promo Banner</span>
                </a>

                <a href="{{ route('admin.flash-sale.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.flash-sale.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-bolt w-6 text-lg {{ request()->routeIs('admin.flash-sale.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Flash Sale</span>
                </a>

                <a href="{{ route('admin.referral.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.referral.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-gift w-6 text-lg {{ request()->routeIs('admin.referral.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Referral Campaign</span>
                </a>

                <a href="{{ route('admin.push-notifications.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.push-notifications.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-paper-plane w-6 text-lg {{ request()->routeIs('admin.push-notifications.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Push Notifications</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Localization</p>

                <a href="{{ route('admin.countries.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.countries.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-globe w-6 text-lg {{ request()->routeIs('admin.countries.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Countries</span>
                </a>

                <a href="{{ route('admin.languages.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.languages.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-language w-6 text-lg {{ request()->routeIs('admin.languages.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Languages</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Content Management</p>

                <a href="{{ route('admin.pages.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.pages.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-file-alt w-6 text-lg {{ request()->routeIs('admin.pages.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Static Pages</span>
                </a>

                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">System</p>

                <a href="{{ route('admin.settings.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.settings.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-cog w-6 text-lg {{ request()->routeIs('admin.settings.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Global Settings</span>
                </a>

                <a href="{{ route('admin.ai.settings') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.ai.settings') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-robot w-6 text-lg {{ request()->routeIs('admin.ai.settings') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>AI Settings</span>
                </a>

                <a href="{{ route('admin.payment-gateways.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.payment-gateways.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-credit-card w-6 text-lg {{ request()->routeIs('admin.payment-gateways.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Payment Gateways</span>
                </a>

                @if(Auth::guard('admin')->user()->hasPermission('manage_staff'))
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-8 mb-4">Access Control</p>

                <a href="{{ route('admin.staff.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.staff.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-user-shield w-6 text-lg {{ request()->routeIs('admin.staff.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Staff Members</span>
                </a>

                <a href="{{ route('admin.roles.index') }}" class="nav-item flex items-center px-4 py-3.5 text-sm font-medium rounded-xl group {{ request()->routeIs('admin.roles.*') ? 'nav-item-active' : 'text-slate-400' }}">
                    <i class="fas fa-key w-6 text-lg {{ request()->routeIs('admin.roles.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                    <span>Roles & Permissions</span>
                </a>
                @endif
            </div>

            <!-- User Profile (Bottom) -->
            <div class="p-6 border-t border-white/10 bg-black/20 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <img src="{{ Auth::guard('admin')->user()->profile_photo_url }}" 
                         alt="{{ Auth::guard('admin')->user()->name ?? 'Admin' }}"
                         class="w-12 h-12 rounded-full shadow-lg border-2 border-white/10 object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ Auth::guard('admin')->user()->email ?? '' }}</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-red-500/20 hover:border-red-500/30 border border-transparent transition-all rounded-xl" title="Logout">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden bg-[#F8FAFC] relative">
            <!-- Top Header -->
            <header class="h-20 glass-panel flex items-center justify-between px-8 z-10 sticky top-0 m-4 rounded-2xl shadow-sm">
                <!-- Mobile Toggle -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-btn" class="text-slate-500 hover:text-slate-800 focus:outline-none p-2 rounded-md hover:bg-slate-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <span class="ml-3 font-bold text-slate-800 text-lg">{{ \App\Models\Setting::get('app_name', config('app.name')) }}</span>
                </div>

                <!-- Page Title / Breadcrumb -->
                <div class="hidden md:flex flex-col justify-center">
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight leading-none mb-1">
                        @yield('header')
                    </h2>
                    <div class="text-xs font-medium text-slate-400 flex items-center gap-2">
                        <span class="hover:text-indigo-500 cursor-pointer transition-colors">Admin</span>
                        <i class="fas fa-chevron-right text-[8px] text-slate-300"></i>
                        <span class="text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-full">@yield('header')</span>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <button class="relative w-10 h-10 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-all rounded-xl hover:bg-indigo-50 border border-transparent hover:border-indigo-100 group">
                        <i class="fas fa-bell text-lg group-hover:animate-swing"></i>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <!-- Search -->
                    <div class="hidden md:block relative group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="text" placeholder="Search anything..." class="pl-11 pr-4 py-2.5 rounded-xl bg-slate-100/50 border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white text-sm w-72 transition-all placeholder-slate-400 text-slate-700 shadow-sm">
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F8FAFC] px-4 sm:px-6 lg:px-8 pb-10 content-transition">
                <div class="max-w-7xl mx-auto pt-6">
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 shadow-sm" role="alert">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3 text-red-700 shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle text-xl"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const closeBtn = document.getElementById('mobile-menu-close');
            const sidebarEl = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            if (mobileBtn && sidebarEl && overlay) {
                function toggleMenu() {
                    const isClosed = sidebarEl.classList.contains('-translate-x-full');
                    
                    if (isClosed) {
                        sidebarEl.classList.remove('-translate-x-full');
                        overlay.classList.remove('hidden');
                        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
                    } else {
                        sidebarEl.classList.add('-translate-x-full');
                        overlay.classList.add('opacity-0');
                        setTimeout(() => overlay.classList.add('hidden'), 300);
                    }
                }

                mobileBtn.addEventListener('click', toggleMenu);
                if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
                overlay.addEventListener('click', toggleMenu);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
