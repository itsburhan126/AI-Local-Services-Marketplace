@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Gig Requests</h1>
            <p class="text-slate-500 text-sm mt-1">Review and approve new gigs from freelancers</p>
        </div>
        <a href="{{ route('admin.freelancers.index') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
            <i class="fas fa-arrow-left"></i> Back to Freelancers
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        <th class="px-6 py-4">Gig Title</th>
                        <th class="px-6 py-4">Freelancer</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gigs as $gig)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if(!empty($gig->images) && count($gig->images) > 0)
                                    <img src="{{ $gig->images[0] }}" class="w-12 h-12 rounded-lg object-cover shadow-sm" alt="Gig">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ Str::limit($gig->title, 40) }}</h3>
                                    <span class="text-xs text-amber-500 font-bold bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">Pending</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    {{ substr($gig->provider->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-slate-600">{{ $gig->provider->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">
                                {{ $gig->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-500">
                                {{ $gig->created_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.gigs.show', $gig->id) }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg text-sm font-bold shadow-md shadow-indigo-200 transition-all">
                                Review Request
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-clipboard-check text-4xl mb-2 opacity-20"></i>
                                <p>No pending gig requests found.</p>
                            </div>
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
