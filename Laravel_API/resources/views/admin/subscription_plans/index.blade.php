@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Subscription Plans</h1>
            <p class="text-gray-500 mt-1">Manage provider subscription packages</p>
        </div>
        <a href="{{ route('admin.subscription-plans.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Create Plan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($plans as $plan)
        <div class="glass-panel rounded-2xl p-6 relative overflow-hidden group hover:shadow-premium transition-all duration-300">
            @if($plan->is_featured)
                <div class="absolute top-0 right-0 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl shadow-md">
                    FEATURED
                </div>
            @endif

            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-800">{{ $plan->name }}</h3>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-bold text-indigo-600">${{ number_format($plan->price, 2) }}</span>
                    <span class="text-gray-500">/ {{ $plan->duration_days }} days</span>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                @if($plan->features)
                    @foreach($plan->features as $feature)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-400 italic">No features listed</p>
                @endif
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <span class="px-3 py-1 rounded-full text-xs font-medium 
                    {{ $plan->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                </span>

                <div class="flex gap-2">
                    <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}" 
                       class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.subscription-plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full glass-panel rounded-2xl p-12 text-center text-gray-500">
            <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-3"></i>
            <p>No subscription plans found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
