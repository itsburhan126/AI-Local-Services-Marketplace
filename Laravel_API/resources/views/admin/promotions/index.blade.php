@extends('layouts.admin')

@section('title', 'Promotions')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Promotions</h1>
            <p class="text-gray-500 mt-1">Manage marketing campaigns and banners</p>
        </div>
        <a href="{{ route('admin.promotions.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Create Promotion
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($promotions as $promotion)
        <div class="glass-panel rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300">
            <div class="h-48 bg-gray-100 relative overflow-hidden">
                <img src="{{ $promotion->image }}" alt="{{ $promotion->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold backdrop-blur-md shadow-sm
                        {{ $promotion->is_active ? 'bg-green-500/90 text-white' : 'bg-gray-500/90 text-white' }}">
                        {{ $promotion->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            
            <div class="p-5">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg text-gray-800 line-clamp-1">{{ $promotion->title }}</h3>
                    <span class="px-2 py-1 rounded bg-indigo-50 text-indigo-600 text-xs font-semibold uppercase tracking-wider">
                        {{ $promotion->type }}
                    </span>
                </div>
                
                <div class="space-y-2 mt-4 mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-calendar-alt w-4"></i>
                        <span>Start: {{ $promotion->start_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-flag-checkered w-4"></i>
                        <span>End: {{ $promotion->end_date->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="flex-1 py-2 rounded-xl bg-gray-50 text-gray-600 font-medium text-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="contents">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full glass-panel rounded-2xl p-12 text-center text-gray-500">
            <div class="flex flex-col items-center justify-center gap-3">
                <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-200 text-2xl mb-2">
                    <i class="fas fa-ad"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">No Promotions Yet</h3>
                <p>Create your first marketing campaign to boost engagement.</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
