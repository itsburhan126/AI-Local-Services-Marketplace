@extends('layouts.admin')

@section('header')
Freelancer Interests Management
@endsection

@section('content')
<div class="space-y-8">
    <!-- Add New Interest Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Add New Freelancer Interest</h3>
        <form action="{{ route('admin.freelancer-interests.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6 md:flex-row md:items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Interest Name</label>
                <input type="text" name="name" required placeholder="e.g. Web Development" 
                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white">
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Icon</label>
                <input type="file" name="icon" required accept="image/*"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Link to Category (Optional)</label>
                <select name="category_id" class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50 focus:bg-white">
                    <option value="">-- No specific category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-200">
                Add Interest
            </button>
        </form>
    </div>

    <!-- Interest Grid -->
    <div>
        <div class="flex justify-between items-center mb-6 px-2">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Available Freelancer Interests</h3>
                <p class="text-sm text-slate-500 mt-1">Drag and drop items to reorder them in the app</p>
            </div>
            <div class="text-sm font-medium text-slate-400 bg-slate-100 px-4 py-2 rounded-lg">
                Total: {{ $interests->total() }}
            </div>
        </div>

        <div id="interests-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @forelse($interests as $interest)
            <div class="group relative bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center gap-4 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 cursor-move" data-id="{{ $interest->id }}">
                <!-- Drag Handle Indicator (Visible on Hover) -->
                <div class="absolute top-3 right-3 text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                </div>

                <!-- Icon -->
                <div class="w-20 h-20 rounded-2xl bg-slate-50 flex items-center justify-center p-4 group-hover:scale-110 transition-transform duration-300">
                    <img src="{{ $interest->icon }}" alt="{{ $interest->name }}" class="w-full h-full object-contain drop-shadow-sm">
                </div>

                <!-- Content -->
                <div class="text-center w-full">
                    <h4 class="font-bold text-slate-800 mb-1 truncate px-2" title="{{ $interest->name }}">{{ $interest->name }}</h4>
                    @if($interest->category)
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-600 uppercase tracking-wide">
                            {{ Str::limit($interest->category->name, 15) }}
                        </span>
                    @else
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-50 text-slate-400 uppercase tracking-wide">
                            General
                        </span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="w-full pt-4 border-t border-slate-50 flex justify-center">
                    <form action="{{ route('admin.freelancer-interests.destroy', $interest) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this interest?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900">No freelancer interests added yet</h3>
                <p class="mt-1 text-slate-500">Get started by adding your first freelancer interest above.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $interests->links() }}
        </div>
    </div>
</div>

<!-- SortableJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('interests-grid');
        var sortable = Sortable.create(el, {
            animation: 200,
            ghostClass: 'opacity-50',
            dragClass: 'scale-105',
            delay: 100, // Slight delay to prevent accidental drags on touch
            delayOnTouchOnly: true,
            onEnd: function (evt) {
                // Get all IDs in the new order
                var ids = [];
                // Since it's a grid of divs now, we iterate through the children
                Array.from(el.children).forEach(function (card) {
                    if(card.hasAttribute('data-id')) {
                        ids.push(card.getAttribute('data-id'));
                    }
                });

                // Send request to update order
                fetch('{{ route("admin.freelancer-interests.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Order updated successfully');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });
</script>
@endsection
