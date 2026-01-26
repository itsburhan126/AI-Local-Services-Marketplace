@extends('layouts.admin')

@section('header', 'Live Activity')

@section('content')
<div class="space-y-8 pb-20">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                Live Customer Activity
            </h2>
            <p class="text-sm text-slate-500">Real-time monitoring of customer interactions</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.flash-sale.index') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Live Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200 relative overflow-hidden">
            <div class="absolute right-0 top-0 p-4 opacity-10">
                <i class="fas fa-users text-8xl"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-indigo-200 text-sm font-bold uppercase tracking-wider">Active Users</h3>
                <p class="text-4xl font-bold mt-2">24</p>
                <p class="text-indigo-200 text-xs mt-1">Currently browsing app</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Service Views</h3>
                    <p class="text-2xl font-bold text-slate-800 mt-1">142</p>
                    <p class="text-emerald-500 text-xs font-bold"><i class="fas fa-arrow-up"></i> 12% vs last hour</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
             <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider">Cart Actions</h3>
                    <p class="text-2xl font-bold text-slate-800 mt-1">18</p>
                    <p class="text-blue-500 text-xs font-bold">Pending checkout</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Live Feed</h3>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">Auto-refreshing</span>
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Service Context</th>
                        <th class="px-6 py-4 text-right">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($activities as $activity)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    @if(isset($activity['user']->profile_image))
                                        <img src="{{ Storage::url($activity['user']->profile_image) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 font-bold border-2 border-white shadow-sm">
                                            {{ substr($activity['user']->name, 0, 1) }}
                                        </div>
                                    @endif
                                    @if($activity['is_online'])
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    @else
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-slate-300 border-2 border-white rounded-full"></span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $activity['user']->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $activity['user']->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($activity['status'] == 'Active')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    Active
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    Idle
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-700 flex items-center gap-2">
                                @if($activity['action'] == 'Viewing')
                                    <i class="fas fa-eye text-blue-400"></i>
                                @elseif($activity['action'] == 'Added to Cart')
                                    <i class="fas fa-cart-plus text-emerald-500"></i>
                                @elseif($activity['action'] == 'Booking Initiated')
                                    <i class="fas fa-file-signature text-purple-500"></i>
                                @else
                                    <i class="fas fa-search text-slate-400"></i>
                                @endif
                                {{ $activity['action'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($activity['service']->image)
                                    <img src="{{ Storage::url($activity['service']->image) }}" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-slate-800 line-clamp-1">{{ $activity['service']->name }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide">{{ $activity['service']->category->name ?? 'Service' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-xs font-medium text-slate-400">{{ $activity['time'] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Mock -->
        <div class="p-4 border-t border-slate-100 flex items-center justify-center">
            <button class="px-4 py-2 text-sm text-slate-500 hover:text-indigo-600 transition-colors">Load More Activity</button>
        </div>
    </div>
</div>
@endsection