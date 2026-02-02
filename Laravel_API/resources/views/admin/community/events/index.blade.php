@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Events</h1>
            <p class="text-slate-500 text-sm mt-1">Manage community events</p>
        </div>
        <a href="#" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all">
            <i class="fas fa-plus"></i> Create Event
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Event</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Time</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Location</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Organizer</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($events as $event)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4">
                                <div class="w-full mb-2">
                                    <img src="{{ $event->image ?? 'https://placehold.co/320x140?text=Event' }}" alt="{{ $event->title }}" class="w-full h-20 object-cover rounded-md border border-slate-100" onerror="this.onerror=null;this.src='https://placehold.co/320x140?text=Event';">
                                </div>
                                <div class="font-bold text-slate-700">{{ $event->title }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600 mt-1">
                                    {{ $event->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-slate-600">
                                <div><i class="far fa-calendar mr-1"></i> {{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBD' }}</div>
                                <div class="mt-1"><i class="far fa-clock mr-1"></i> {{ $event->start_date ? $event->start_date->format('h:i A') : '' }}</div>
                            </td>
                            <td class="p-4 text-sm text-slate-600">
                                {{ $event->location }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs">
                                        {{ substr($event->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-600">{{ $event->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <button class="text-slate-400 hover:text-indigo-600 transition-colors mr-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-slate-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">
                                No events found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
