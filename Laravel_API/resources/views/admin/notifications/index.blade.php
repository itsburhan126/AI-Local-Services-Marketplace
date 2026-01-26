@extends('layouts.admin')

@section('title', 'Push Notifications')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Push Notifications</h1>
            <p class="text-gray-500 mt-1">Send marketing alerts to users and providers</p>
        </div>
        <a href="{{ route('admin.push-notifications.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-paper-plane transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5"></i> Send Notification
        </a>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Title / Message</th>
                        <th class="px-4 py-4">Audience</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Date</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($notifications as $notification)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-start gap-3">
                                @if($notification->image)
                                    <img src="{{ $notification->image }}" alt="Icon" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $notification->title }}</h3>
                                    <p class="text-sm text-gray-500 line-clamp-1">{{ $notification->body }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-600 text-xs font-semibold uppercase tracking-wider">
                                {{ $notification->target_audience }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($notification->is_sent)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i> Sent
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-600">
                                    <i class="fas fa-clock mr-1"></i> Scheduled
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500">
                            @if($notification->scheduled_at)
                                {{ $notification->scheduled_at->format('M d, Y h:i A') }}
                            @else
                                {{ $notification->sent_at ? $notification->sent_at->format('M d, Y h:i A') : '-' }}
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            <form action="{{ route('admin.push-notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-red-600 transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                <p>No notifications history</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
