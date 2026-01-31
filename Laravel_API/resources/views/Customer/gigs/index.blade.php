@extends('layouts.customer')

@section('title', 'All Gigs')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">All Gigs</h1>
        
        <!-- Filter/Sort -->
        <div class="flex flex-wrap gap-4 items-center">
            
            <!-- Category Filter -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:border-emerald-500 hover:text-emerald-600 transition-all shadow-sm min-w-[160px] justify-between">
                    <span class="truncate">
                        @php
                            $currentCat = $categories->firstWhere('id', request('category'));
                            if (!$currentCat && isset($subcategory)) {
                                $currentCat = $subcategory;
                            }
                        @endphp
                        {{ $currentCat ? $currentCat->name : 'All Categories' }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 z-50 max-h-[300px] overflow-y-auto py-2">
                    <a href="{{ route('customer.gigs.index', array_merge(request()->except('category'), ['category' => null])) }}" class="block px-4 py-2.5 text-sm hover:bg-emerald-50 hover:text-emerald-600 {{ !request('category') ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">
                        All Categories
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('customer.gigs.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}" class="block px-4 py-2.5 text-sm hover:bg-emerald-50 hover:text-emerald-600 {{ request('category') == $cat->id ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:border-emerald-500 hover:text-emerald-600 transition-all shadow-sm min-w-[140px] justify-between">
                    <span class="truncate">
                        @switch(request('sort'))
                            @case('popular') Most Popular @break
                            @case('rating') Highest Rated @break
                            @case('oldest') Oldest @break
                            @default Newest
                        @endswitch
                    </span>
                    <svg class="w-4 h-4 text-gray-400" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 z-50 py-2">
                    <a href="{{ route('customer.gigs.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" class="block px-4 py-2.5 text-sm hover:bg-emerald-50 hover:text-emerald-600 {{ request('sort') == 'newest' || !request('sort') ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">Newest</a>
                    <a href="{{ route('customer.gigs.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" class="block px-4 py-2.5 text-sm hover:bg-emerald-50 hover:text-emerald-600 {{ request('sort') == 'popular' ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">Most Popular</a>
                    <a href="{{ route('customer.gigs.index', array_merge(request()->except('sort'), ['sort' => 'rating'])) }}" class="block px-4 py-2.5 text-sm hover:bg-emerald-50 hover:text-emerald-600 {{ request('sort') == 'rating' ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">Highest Rated</a>
                    <a href="{{ route('customer.gigs.index', array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}" class="block px-4 py-2.5 text-sm hover:bg-emerald-50 hover:text-emerald-600 {{ request('sort') == 'oldest' ? 'text-emerald-600 font-medium bg-emerald-50/50' : 'text-gray-700' }}">Oldest</a>
                </div>
            </div>

        </div>
    </div>

    @if($gigs->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($gigs as $gig)
                @include('Customer.components.gig-card', ['gig' => $gig])
            @endforeach
        </div>

        <div class="mt-8">
            {{ $gigs->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🔍</div>
            <h3 class="text-lg font-bold text-gray-900">No gigs found</h3>
            <p class="text-gray-500">Try adjusting your filters or search query.</p>
        </div>
    @endif
</div>
@endsection
