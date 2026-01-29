@extends('layouts.freelancer')

@section('title', 'My Gigs')

@section('content')
<div class="w-full mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">My Gigs</h2>
            <p class="text-slate-500 mt-1">Manage your services and offers.</p>
        </div>
        <a href="{{ route('provider.freelancer.gigs.create') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40">
            <i class="fas fa-plus"></i> Create New Gig
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600 text-xl">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">Active Gigs</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $gigs->where('is_active', true)->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 text-xl">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">Pending Approval</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $gigs->where('status', 'pending')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">Total Views</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $gigs->sum('view_count') }}</h3>
            </div>
        </div>
    </div>

    <!-- Gigs List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @if($gigs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Gig Service</th>
                        <th class="px-6 py-4">Impressions</th>
                        <th class="px-6 py-4">Orders</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($gigs as $gig)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-24 flex-shrink-0 rounded-lg bg-slate-100 overflow-hidden border border-slate-200">
                                    @if($gig->thumbnail_image)
                                        <img src="{{ asset('storage/' . $gig->thumbnail_image) }}" alt="{{ $gig->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-400">
                                            <i class="fas fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="max-w-md">
                                    <h3 class="font-bold text-slate-800 text-base truncate group-hover:text-primary-600 transition-colors">{{ $gig->title }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $gig->category->name ?? 'Category' }}</span>
                                        @if($gig->packages->isNotEmpty())
                                            <span class="text-xs font-bold text-slate-700">From ${{ $gig->packages->min('price') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">{{ $gig->view_count }}</span>
                                <span class="text-xs text-slate-400">Total Views</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">0</span>
                                <span class="text-xs text-slate-400">Active Orders</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($gig->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Active
                                </span>
                            @elseif($gig->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> {{ ucfirst($gig->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('provider.freelancer.gigs.edit', $gig->id) }}" class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('provider.freelancer.gigs.destroy', $gig->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this gig?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <button class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" title="More">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $gigs->links() }}
        </div>
        @else
        <div class="p-12 text-center flex flex-col items-center justify-center">
            <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-box-open text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">No Gigs Found</h3>
            <p class="text-slate-500 mb-6 max-w-sm">You haven't created any gigs yet. Start selling your services today!</p>
            <a href="{{ route('provider.freelancer.gigs.create') }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-lg shadow-primary-500/30">
                <i class="fas fa-plus"></i> Create Your First Gig
            </a>
        </div>
        @endif
    </div>
</div>
@endsection