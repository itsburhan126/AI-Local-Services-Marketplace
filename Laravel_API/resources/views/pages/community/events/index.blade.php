@extends('layouts.customer')

@section('title', 'Community Events')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Community Events</h1>
                <p class="text-gray-600 mt-1">Workshops, meetups, and live sessions for our community.</p>
            </div>
            <a href="{{ route('community.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Back to Community</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                <a href="{{ route('community.events.show', $event->slug) }}" class="group bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition">
                    <img src="{{ $event->image ?? 'https://placehold.co/800x320?text=Event' }}" alt="{{ $event->title }}" class="h-40 w-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/800x320?text=Event';">
                    <div class="p-6">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 bg-indigo-50 rounded-lg p-2 text-center min-w-[56px]">
                                <span class="block text-indigo-600 font-bold text-lg">{{ $event->start_date->format('d') }}</span>
                                <span class="block text-indigo-500 text-xs uppercase">{{ $event->start_date->format('M') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 truncate">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1 truncate">
                                    <i class="far fa-clock mr-1"></i> {{ $event->start_date->format('D, M d · h:i A') }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1 truncate">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->is_online ? 'Online' : $event->location }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No upcoming events</h3>
                    <p class="text-gray-500 mt-1">Check back later for new events.</p>
                </div>
            @endforelse
        </div>

        @if($events->hasPages())
            <div class="bg-gray-50 px-6 py-4 mt-6 border-t border-gray-200 rounded-lg">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
