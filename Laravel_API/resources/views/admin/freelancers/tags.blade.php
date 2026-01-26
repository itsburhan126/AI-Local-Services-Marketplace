@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tag Management</h1>
            <p class="text-slate-500 text-sm mt-1">Manage tags, view usage statistics, and add new system tags.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.freelancers.index') }}" class="glass-panel px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-indigo-600 hover:bg-white/80 transition-all">
                <i class="fas fa-arrow-left"></i>
                <span>Back</span>
             </a>
             <button onclick="openModal('createTagModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i>
                <span>Add New Tag</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tags Table -->
    <div class="glass-panel rounded-2xl overflow-hidden shadow-premium">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <h3 class="font-bold text-lg text-slate-800">All Tags</h3>
                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-xs font-bold">{{ $tags->total() }}</span>
            </div>
             <form action="{{ route('admin.freelancers.tags') }}" method="GET" class="relative w-full sm:w-72">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tags..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none text-sm transition-all bg-white/50 focus:bg-white">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tag Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Usage</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Used By (Preview)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tags as $tag)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm font-bold border border-slate-200">
                                <span class="text-slate-400">#</span>{{ $tag->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($tag->source === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100">
                                    <i class="fas fa-shield-alt text-[10px]"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-50 text-orange-700 text-xs font-bold border border-orange-100">
                                    <i class="fas fa-user text-[10px]"></i> Provider
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-700 text-lg">{{ $tag->gigs_count }}</span>
                                <span class="text-xs text-slate-400 font-medium">gigs</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($tag->gigs->count() > 0)
                                <div class="flex items-center -space-x-2 overflow-hidden py-1">
                                    @foreach($tag->gigs->take(4) as $gig)
                                        <div class="relative group cursor-pointer">
                                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover" 
                                                 src="{{ $gig->provider->avatar ?? 'https://ui-avatars.com/api/?background=random&name='.urlencode($gig->provider->name) }}" 
                                                 alt="{{ $gig->provider->name }}"
                                                 title="{{ $gig->provider->name }} - {{ $gig->title }}">
                                        </div>
                                    @endforeach
                                    @if($tag->gigs_count > 4)
                                        <div class="flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-slate-100 text-[10px] font-bold text-slate-500">
                                            +{{ $tag->gigs_count - 4 }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No usage yet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $tag->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $tag->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $tag->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Delete Tag">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-tags text-slate-300 text-2xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-bold mb-1">No tags found</h3>
                                <p class="text-slate-500 text-sm">Try adjusting your search or add a new tag.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
         <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $tags->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

<!-- Create Tag Modal -->
<div id="createTagModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm transition-opacity" onclick="closeModal('createTagModal')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            <form action="{{ route('admin.freelancers.tags.store') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-tag text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Create New Tag</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Add a new tag that providers can use for their gigs.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Tag Name</label>
                                        <input type="text" name="name" id="name" required class="w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3 border" placeholder="e.g. Logo Design">
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input id="is_active" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="is_active" class="ml-2 block text-sm text-slate-700">Active status</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-colors">Create Tag</button>
                    <button type="button" onclick="closeModal('createTagModal')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection
