@extends('layouts.customer')

@section('title', $event->title)

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ $event->image ?? 'https://placehold.co/1200x360?text=Event' }}" alt="{{ $event->title }}" class="h-64 w-full object-cover" onerror="this.onerror=null;this.src='https://placehold.co/1200x360?text=Event';">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 bg-indigo-50 rounded-lg p-3 text-center min-w-[72px]">
                        <span class="block text-indigo-600 font-bold text-2xl leading-none">{{ $event->start_date->format('d') }}</span>
                        <span class="block text-indigo-500 text-xs uppercase">{{ $event->start_date->format('M') }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">{{ $event->category->name }}</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <span class="mr-4"><i class="far fa-clock mr-1"></i>{{ $event->start_date->format('D, M d · h:i A') }}</span>
                            @if($event->end_date)
                                <span class="mr-4">– {{ $event->end_date->format('D, M d · h:i A') }}</span>
                            @endif
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->is_online ? 'Online' : $event->location }}</span>
                        </div>
                        <div class="mt-6 prose max-w-none text-gray-800">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                        <div class="mt-6 flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                <span class="mr-4 inline-flex items-center"><i class="far fa-user-friends mr-1"></i>{{ $event->attendees->count() }} going</span>
                                @if($event->max_attendees)
                                    <span class="inline-flex items-center"><i class="far fa-user mr-1"></i>Max {{ $event->max_attendees }}</span>
                                @endif
                            </div>
                            <div>
                                @auth('web')
                                    <form action="{{ route('community.events.attend', $event->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium {{ $isAttending ? 'text-gray-700 bg-gray-100 hover:bg-gray-200' : 'text-white bg-indigo-600 hover:bg-indigo-700' }}">
                                            <i class="{{ $isAttending ? 'fas fa-check mr-2' : 'fas fa-plus mr-2' }}"></i>
                                            {{ $isAttending ? 'Attending' : 'Attend' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('customer.login') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                        Log in to attend
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Attendees</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($event->attendees as $attendee)
                        <div class="flex items-center gap-3">
                            @if($attendee->user->profile_photo_url)
                                <img class="h-10 w-10 rounded-full" src="{{ $attendee->user->profile_photo_url }}" alt="">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                    {{ substr($attendee->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $attendee->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $attendee->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500">No attendees yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
