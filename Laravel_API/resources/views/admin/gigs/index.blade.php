@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">All Gigs</h1>
            <p class="text-slate-500 text-sm mt-1">Manage all freelance gigs</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.gigs.requests') }}" class="bg-amber-50 border border-amber-200 text-amber-600 hover:bg-amber-100 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
                <i class="fas fa-bell"></i> Pending Requests
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex gap-2">
        <a href="{{ route('admin.gigs.index') }}" class="px-4 py-2 rounded-lg text-sm font-bold {{ !request('status') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            All
        </a>
        <a href="{{ route('admin.gigs.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg text-sm font-bold {{ request('status') == 'approved' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            Approved
        </a>
        <a href="{{ route('admin.gigs.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-sm font-bold {{ request('status') == 'rejected' ? 'bg-red-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
            Rejected
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        <th class="px-6 py-4">Gig Title</th>
                        <th class="px-6 py-4">Freelancer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gigs as $gig)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if(!empty($gig->thumbnail_image))
                                    @php
                                        $thumbnail = $gig->thumbnail_image;
                                        if (Str::startsWith($thumbnail, ['http://', 'https://'])) {
                                            $thumbnailUrl = $thumbnail;
                                        } else {
                                            $thumbnailUrl = asset(Str::startsWith($thumbnail, 'storage/') ? $thumbnail : 'storage/' . $thumbnail);
                                        }
                                    @endphp
                                    <img src="{{ $thumbnailUrl }}" class="w-10 h-10 rounded-lg object-cover shadow-sm" alt="Gig">
                                @elseif(!empty($gig->images) && count($gig->images) > 0)
                                    @php
                                        $image = $gig->images[0];
                                        if (Str::startsWith($image, ['http://', 'https://'])) {
                                            $imageUrl = $image;
                                        } else {
                                            $imageUrl = asset(Str::startsWith($image, 'storage/') ? $image : 'storage/' . $image);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" class="w-10 h-10 rounded-lg object-cover shadow-sm" alt="Gig">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                @endif
                                <h3 class="font-bold text-slate-800 text-sm">{{ Str::limit($gig->title, 30) }}</h3>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-600">{{ $gig->provider->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2 py-1 rounded-full border 
                                {{ $gig->status === 'approved' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                {{ $gig->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                {{ $gig->status === 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : '' }}
                            ">
                                {{ ucfirst($gig->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500">
                                {{ $gig->created_at->format('M d, Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.gigs.show', $gig->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            No gigs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($gigs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $gigs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
