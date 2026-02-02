@extends('layouts.customer')

@section('title', 'All Interests')

@section('content')
<main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm relative overflow-hidden">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-display mb-2">What Sparks Your Interest?</h1>
                <p class="text-gray-500 text-base">Select topics to personalize your feed.</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <!-- Grid of Interests -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-data="{
                toggleInterest(id) {
                    const btn = this.$refs['interest-' + id];
                    const isAdded = btn.getAttribute('data-added') === 'true';
                    const label = btn.querySelector('.status-label');
                    const icon = btn.querySelector('.status-icon');
                    
                    // Optimistic Update
                    if(isAdded) {
                        // Switch to Removed state
                        btn.setAttribute('data-added', 'false');
                        btn.classList.remove('border-emerald-500', 'bg-emerald-50');
                        btn.classList.add('border-gray-100', 'bg-white');
                        
                        // Update button text/icon
                        label.innerText = 'Add';
                        // Add icon path
                        icon.innerHTML = '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\' />';
                        icon.parentNode.classList.remove('text-emerald-600', 'border-emerald-500', 'bg-emerald-50');
                        icon.parentNode.classList.add('text-gray-700', 'border-gray-200');
                        icon.parentNode.classList.add('group-hover/card:border-emerald-500', 'group-hover/card:text-emerald-600', 'group-hover/card:bg-emerald-50'); // Re-add hover classes

                    } else {
                        // Switch to Added state
                        btn.setAttribute('data-added', 'true');
                        btn.classList.remove('border-gray-100', 'bg-white');
                        btn.classList.add('border-emerald-500', 'bg-emerald-50');
                        
                        // Update button text/icon
                        label.innerText = 'Added';
                        // Checkmark icon path
                        icon.innerHTML = '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\' />';
                        icon.parentNode.classList.remove('text-gray-700', 'border-gray-200');
                        icon.parentNode.classList.add('text-emerald-600', 'border-emerald-500', 'bg-emerald-50');
                        icon.parentNode.classList.remove('group-hover/card:border-emerald-500', 'group-hover/card:text-emerald-600', 'group-hover/card:bg-emerald-50'); // Remove hover classes to keep active state
                    }
                    
                    fetch('{{ route('customer.interests.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ category_id: id })
                    }).then(res => res.json())
                    .then(data => {
                        if(data.status !== 'success') {
                            // Revert if failed
                            alert('Something went wrong. Please try again.');
                            window.location.reload();
                        }
                    });
                }
            }">
            
            @foreach($interests as $interest)
                @php
                    $isAdded = in_array($interest->id, $userInterestCategoryIds);
                @endphp
                
                <button x-ref="interest-{{ $interest->id }}" 
                        data-added="{{ $isAdded ? 'true' : 'false' }}"
                        @click="@auth toggleInterest({{ $interest->id }}) @else window.location.href = '{{ route('customer.login') }}' @endauth"
                        class="w-full group/card flex items-center justify-between p-6 border rounded-3xl hover:shadow-lg hover:shadow-emerald-50 transition-all duration-300 relative overflow-hidden {{ $isAdded ? 'border-emerald-500 bg-emerald-50' : 'bg-white border-gray-100' }}">
                    
                    <div class="flex items-center gap-4 z-10">
                        <div class="w-10 h-10 flex items-center justify-center text-gray-400 group-hover/card:text-emerald-500 transition-colors">
                            @php
                                $imagePath = $interest->image ?? $interest->icon;
                            @endphp
                            @if($imagePath)
                                @php
                                    $iconSrc = \Illuminate\Support\Str::startsWith($imagePath, ['http', 'https']) ? $imagePath : asset($imagePath);
                                @endphp
                                <img src="{{ $iconSrc }}" class="w-full h-full object-contain opacity-60 group-hover/card:opacity-100 transition-opacity" alt="">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            @endif
                        </div>
                        <span class="font-bold text-gray-800 text-lg group-hover/card:text-gray-900 transition-colors text-left">{{ $interest->name }}</span>
                    </div>
                    
                    <div class="z-10 px-5 py-2 rounded-full border text-sm font-bold transition-all flex items-center gap-2 {{ $isAdded ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-gray-200 text-gray-700 bg-white group-hover/card:border-emerald-500 group-hover/card:text-emerald-600 group-hover/card:bg-emerald-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 status-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            @if($isAdded)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            @endif
                        </svg>
                        <span class="status-label">{{ $isAdded ? 'Added' : 'Add' }}</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>
</main>
@endsection
