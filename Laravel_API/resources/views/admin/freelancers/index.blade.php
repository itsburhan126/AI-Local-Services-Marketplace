@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Freelancer Management</h1>
            <p class="text-slate-500 text-sm mt-1">Manage freelancers, gigs, and performance</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.gigs.requests') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-bell"></i> Gig Requests
            </a>
            <a href="{{ route('admin.freelancer-categories.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
                <i class="fas fa-th-large"></i> Categories
            </a>
            <a href="{{ route('admin.freelancer-interests.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
                <i class="fas fa-heart"></i> Interests
            </a>
            <a href="{{ route('admin.skills.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
                <i class="fas fa-tools"></i> Skills
            </a>
           
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
        <!-- Total Freelancers -->
        <div class="premium-card p-6 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-user-tie text-4xl text-indigo-500 transform rotate-12 translate-x-2 -translate-y-2"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Freelancers</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_freelancers'] }}</h3>
            <p class="text-[10px] text-indigo-500 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-arrow-up"></i> Active
            </p>
        </div>

        <!-- Pending Approvals -->
        <div class="premium-card p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-user-clock text-4xl text-amber-500 transform rotate-12 translate-x-2 -translate-y-2"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Approval</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pending_freelancers'] }}</h3>
            <p class="text-[10px] text-amber-500 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-clock"></i> Action Needed
            </p>
        </div>

        <!-- Total Gigs -->
        <div class="premium-card p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-briefcase text-4xl text-emerald-500 transform rotate-12 translate-x-2 -translate-y-2"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Gigs</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_gigs'] }}</h3>
            <p class="text-[10px] text-emerald-500 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-check-circle"></i> Published
            </p>
        </div>
        
         <!-- Active Gigs -->
        <div class="premium-card p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-rocket text-4xl text-purple-500 transform rotate-12 translate-x-2 -translate-y-2"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Gigs</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['active_gigs'] }}</h3>
            <p class="text-[10px] text-purple-500 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-bolt"></i> Live Now
            </p>
        </div>
        
         <!-- Service Types -->
        <div class="premium-card p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-layer-group text-4xl text-blue-500 transform rotate-12 translate-x-2 -translate-y-2"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Service Types</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_service_types'] }}</h3>
            <p class="text-[10px] text-blue-500 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-list"></i> Categories
            </p>
        </div>

        <!-- Total Tags -->
        <div class="premium-card p-6 relative overflow-hidden group">
             <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fas fa-tags text-4xl text-pink-500 transform rotate-12 translate-x-2 -translate-y-2"></i>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tags</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_tags'] }}</h3>
            <p class="text-[10px] text-pink-500 font-bold mt-1 flex items-center gap-1">
                <i class="fas fa-hashtag"></i> Keywords
            </p>
        </div>
    </div>
    <!-- Configuration Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.freelancer-categories.index') }}" class="group bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-th-large"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">Categories</h4>
                    <p class="text-xs text-slate-400">Manage structure</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.freelancer-interests.index') }}" class="group bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-pink-50 text-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-heart"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">Interests</h4>
                    <p class="text-xs text-slate-400">User preferences</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.freelancers.index', ['tab' => 'service_types']) }}" class="group bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">Service Types</h4>
                    <p class="text-xs text-slate-400">Gig types</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.freelancers.index', ['tab' => 'tags']) }}" class="group bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">Tags</h4>
                    <p class="text-xs text-slate-400">Search keywords</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.freelancers.banners') }}" class="group bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-images"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">Banners</h4>
                    <p class="text-xs text-slate-400">Promotional images</p>
                </div>
            </div>
        </a>
        <a href="{{ route('admin.freelancers.settings') }}" class="group bg-white p-4 rounded-xl border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm group-hover:text-indigo-600 transition-colors">Delivery Settings</h4>
                    <p class="text-xs text-slate-400">Payment & Pending</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-slate-200 overflow-x-auto">
        <div class="flex gap-8 min-w-max">
            <a href="{{ route('admin.freelancers.index', ['tab' => 'overview']) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition-colors {{ $tab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Overview
            </a>
            <a href="{{ route('admin.freelancers.index', ['tab' => 'freelancers']) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition-colors {{ $tab === 'freelancers' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Freelancers List
            </a>
            <a href="{{ route('admin.freelancers.index', ['tab' => 'gigs']) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition-colors {{ $tab === 'gigs' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Gigs Management
            </a>
            <a href="{{ route('admin.freelancers.index', ['tab' => 'service_types']) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition-colors {{ $tab === 'service_types' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Service Types
            </a>
            <a href="{{ route('admin.freelancers.index', ['tab' => 'tags']) }}" 
               class="pb-4 text-sm font-bold border-b-2 transition-colors {{ $tab === 'tags' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                Tags
            </a>
        </div>
    </div>


    <!-- Content Area -->
    <div class="content-transition">
        @if($tab === 'overview')
            <!-- Overview Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                 <!-- Recent Freelancers -->
                 <div class="glass-panel rounded-2xl p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Recent Freelancers</h3>
                    <div class="space-y-4">
                        @foreach($freelancers->take(5) as $freelancer)
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                             <img src="{{ $freelancer->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($freelancer->name) }}" class="w-10 h-10 rounded-full object-cover">
                             <div>
                                 <h4 class="font-bold text-slate-800 text-sm">{{ $freelancer->name }}</h4>
                                 <p class="text-xs text-slate-500">{{ $freelancer->email }}</p>
                             </div>
                             <a href="{{ route('admin.providers.show', $freelancer->id) }}" class="ml-auto text-xs font-bold text-indigo-500 hover:text-indigo-600">View</a>
                        </div>
                        @endforeach
                    </div>
                 </div>

                 <!-- Recent Gigs -->
                 <div class="glass-panel rounded-2xl p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Recent Gigs</h3>
                     <div class="space-y-4">
                        @foreach($gigs->take(5) as $gig)
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                             <img src="{{ $gig->image_url ?? 'https://via.placeholder.com/150' }}" class="w-10 h-10 rounded-lg object-cover">
                             <div>
                                 <h4 class="font-bold text-slate-800 text-sm">{{ Str::limit($gig->title, 30) }}</h4>
                                 <p class="text-xs text-slate-500">{{ $gig->provider->name ?? 'Unknown' }}</p>
                             </div>
                             <a href="{{ route('admin.gigs.show', $gig->id) }}" class="ml-auto text-xs font-bold text-indigo-500 hover:text-indigo-600">View</a>
                        </div>
                        @endforeach
                    </div>
                 </div>
            </div>

        @elseif($tab === 'freelancers')
            <!-- Freelancers Table -->
            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-left">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Freelancer</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($freelancers as $freelancer)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $freelancer->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($freelancer->name) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $freelancer->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $freelancer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $freelancer->status === 'active' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                        {{ ucfirst($freelancer->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $freelancer->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.providers.show', $freelancer->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $freelancers->appends(['tab' => 'freelancers'])->links() }}
                </div>
            </div>

        @elseif($tab === 'gigs')
            <!-- Gigs Table -->
            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex gap-4">
                     <form action="{{ route('admin.freelancers.index') }}" method="GET" class="flex-1 flex gap-2">
                        <input type="hidden" name="tab" value="gigs">
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search gigs..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm transition-all">
                        </div>
                        <select name="status" class="px-4 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-500 text-white font-bold text-sm hover:bg-indigo-600 transition-colors">
                            Filter
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-left">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Gig</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Freelancer</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($gigs as $gig)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-slate-200 overflow-hidden flex-shrink-0">
                                            @if($gig->image_url)
                                                <img src="{{ $gig->image_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm line-clamp-1">{{ $gig->title }}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                @if($gig->is_featured)
                                                    <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-600 text-[10px] font-bold uppercase">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $gig->provider->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($gig->provider->name ?? 'User') }}" class="w-6 h-6 rounded-full">
                                        <span class="text-sm font-medium text-slate-700">{{ $gig->provider->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $gig->category->name ?? 'N/A' }}
                                    <span class="text-slate-400 text-xs block">{{ $gig->serviceType->name ?? '' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                    ${{ number_format($gig->price, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $gig->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $gig->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.gigs.show', $gig->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                 <div class="px-6 py-4 border-t border-slate-100">
                    {{ $gigs->appends(['tab' => 'gigs', 'search' => request('search'), 'status' => request('status')])->links() }}
                </div>
            </div>

        @elseif($tab === 'service_types')
            <!-- Service Types Table -->
            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">All Service Types</h3>
                    <button onclick="openModal('createServiceTypeModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        <span>Add New Type</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-left">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($serviceTypes as $type)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800 text-sm">{{ $type->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">{{ $type->slug }}</code>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $type->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $type->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.service-types.destroy', $type->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this service type?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                 <div class="px-6 py-4 border-t border-slate-100">
                    {{ $serviceTypes->appends(['tab' => 'service_types'])->links() }}
                </div>
            </div>

        @elseif($tab === 'tags')
            <!-- Tags Table -->
            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-slate-800">All Tags</h3>
                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-xs font-bold">{{ $tags->total() }}</span>
                    </div>
                    <div class="flex gap-3">
                         <form action="{{ route('admin.freelancers.index') }}" method="GET" class="relative hidden sm:block">
                            <input type="hidden" name="tab" value="tags">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tags..." class="pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm transition-all bg-white">
                        </form>
                        <button onclick="openModal('createTagModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-plus"></i>
                            <span>Add New Tag</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-left">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tag Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Usage</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Used By (Preview)</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($tags as $tag)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm font-bold border border-slate-200">
                                        <span class="text-slate-400">#</span>{{ $tag->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($tag->source === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                                            <i class="fas fa-shield-alt text-[10px]"></i> Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-50 text-orange-700 text-xs font-bold border border-orange-100">
                                            <i class="fas fa-user text-[10px]"></i> Provider
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-700 text-lg">{{ $tag->gigs_count }}</span>
                                        <span class="text-xs text-slate-400 font-medium">gigs</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($tag->gigs->count() > 0)
                                        <div class="flex items-center -space-x-2 overflow-hidden py-1">
                                            @foreach($tag->gigs->take(4) as $gig)
                                                <div class="relative group cursor-pointer">
                                                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover" 
                                                         src="{{ $gig->provider->avatar ?? 'https://ui-avatars.com/api/?background=random&name='.urlencode($gig->provider->name) }}" 
                                                         alt="{{ $gig->provider->name }}"
                                                         title="{{ $gig->provider->name }} - {{ $gig->title }}">
                                                </div>
                                            @endforeach
                                            @if($tag->gigs_count > 4)
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-slate-100 text-[10px] font-bold text-slate-500">
                                                    +{{ $tag->gigs_count - 4 }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">No usage yet</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $tag->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $tag->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                        {{ $tag->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openEditTagModal('{{ $tag->id }}', '{{ addslashes($tag->name) }}', '{{ $tag->is_active }}')" 
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-indigo-500 hover:bg-indigo-50 transition-colors" title="Edit Tag">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.freelancers.tags.destroy', $tag->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Delete Tag">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-tags text-slate-300 text-2xl"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold mb-1">No tags found</h3>
                                        <p class="text-slate-500 text-sm">Try adjusting your search or add a new tag.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 <div class="px-6 py-4 border-t border-slate-100">
                    {{ $tags->appends(['tab' => 'tags', 'search' => request('search')])->links() }}
                </div>
            </div>
        @endif
    </div>
</div>



<!-- Create Service Type Modal -->
<div id="createServiceTypeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm transition-opacity" onclick="closeModal('createServiceTypeModal')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            <form action="{{ route('admin.service-types.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="freelancer">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-layer-group text-purple-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Create Service Type</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Add a new service type for freelancers to categorize their gigs.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Type Name</label>
                                        <input type="text" name="name" id="name" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border" placeholder="e.g. Remote Service">
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input id="is_active" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="is_active" class="ml-2 block text-sm text-slate-700">Active status</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">Create Type</button>
                    <button type="button" onclick="closeModal('createServiceTypeModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Tag Modal -->
<div id="createTagModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm transition-opacity" onclick="closeModal('createTagModal')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            <form action="{{ route('admin.freelancers.tags.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-tag text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Create New Tag</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Add a new tag that providers can use for their gigs.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="tag_name" class="block text-sm font-medium text-slate-700 mb-1">Tag Name</label>
                                        <input type="text" name="name" id="tag_name" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border" placeholder="e.g. Logo Design">
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input id="tag_is_active" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="tag_is_active" class="ml-2 block text-sm text-slate-700">Active status</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">Create Tag</button>
                    <button type="button" onclick="closeModal('createTagModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tag Modal -->
<div id="editTagModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm transition-opacity" onclick="closeModal('editTagModal')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            <form id="editTagForm" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-edit text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Edit Tag</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Update the tag details.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="edit_tag_name" class="block text-sm font-medium text-slate-700 mb-1">Tag Name</label>
                                        <input type="text" name="name" id="edit_tag_name" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border">
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input id="edit_tag_is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="edit_tag_is_active" class="ml-2 block text-sm text-slate-700">Active status</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">Update Tag</button>
                    <button type="button" onclick="closeModal('editTagModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function openEditTagModal(id, name, isActive) {
        const form = document.getElementById('editTagForm');
        form.action = "{{ url('admin/freelancers/tags') }}/" + id;
        
        document.getElementById('edit_tag_name').value = name;
        document.getElementById('edit_tag_is_active').checked = isActive == 1;
        
        openModal('editTagModal');
    }
</script>
@endsection
