@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Testimonials</h1>
            <p class="text-slate-500 text-sm mt-1">Manage customer testimonials</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Add Testimonial
        </a>
    </div>

    <div class="mb-4 flex items-center gap-2 text-sm text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-200">
        <i class="fas fa-info-circle text-indigo-500"></i>
        <span>Drag and drop the cards to reorder them. The order updates automatically.</span>
    </div>

    <div id="testimonial-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($testimonials as $testimonial)
        <div class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md hover:border-indigo-200 transition-all cursor-move" data-id="{{ $testimonial->id }}">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img src="{{ $testimonial->image_url }}" class="w-14 h-14 rounded-full object-cover border-2 border-slate-100 group-hover:border-indigo-100 transition-colors" alt="{{ $testimonial->name }}">
                        <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-1 shadow-sm border border-slate-100 text-slate-400 group-hover:text-indigo-500">
                            <i class="fas fa-grip-lines text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">{{ $testimonial->name }}</h3>
                        <p class="text-slate-500 text-sm">{{ $testimonial->role }}</p>
                    </div>
                </div>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex text-yellow-400 text-sm mb-3">
                    @for($i = 0; $i < $testimonial->rating; $i++)
                        <i class="fas fa-star"></i>
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic line-clamp-3">"{{ $testimonial->text }}"</p>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $testimonial->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-100' }}">
                    {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                </span>
                
                <span class="text-xs font-mono text-slate-400">
                    Order: <span class="order-display">{{ $testimonial->order }}</span>
                </span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $testimonials->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var grid = document.getElementById('testimonial-grid');
        new Sortable(grid, {
            animation: 150,
            ghostClass: 'bg-indigo-50',
            onEnd: function() {
                var ids = [];
                document.querySelectorAll('#testimonial-grid > div').forEach(function(el, index) {
                    ids.push(el.getAttribute('data-id'));
                    // Optimistically update the visible order number
                    el.querySelector('.order-display').textContent = index;
                });

                // Send AJAX request to update order
                fetch('{{ route("admin.testimonials.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Optional: Show a toast notification
                        console.log('Order updated');
                    }
                });
            }
        });
    });
</script>
@endsection