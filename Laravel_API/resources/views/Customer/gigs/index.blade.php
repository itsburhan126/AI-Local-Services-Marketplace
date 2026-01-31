@extends('layouts.customer')

@section('title', 'All Gigs')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">All Gigs</h1>
        
        <!-- Filter/Sort (Simple placeholder) -->
        <div class="flex gap-4">
            <select onchange="window.location.href=this.value" class="border-gray-300 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                <option value="{{ route('gigs.index') }}">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ route('gigs.index', ['category' => $cat->id]) }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if($gigs->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
