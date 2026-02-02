@foreach($stories as $story)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
    <div class="h-48 relative overflow-hidden group bg-gray-100">
        <a href="{{ route('success-stories.show', $story->id) }}" class="block w-full h-full">
            @if(!empty($story->image_path))
                <img src="{{ asset('storage/' . $story->image_path) }}" 
                     alt="{{ $story->name }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                <div class="hidden w-full h-full bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center">
                    <i class="fas fa-quote-right text-4xl text-white/20"></i>
                </div>
            @else
                <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center">
                    <i class="fas fa-quote-right text-4xl text-white/20"></i>
                </div>
            @endif
        </a>
        <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-gray-900 shadow-sm pointer-events-none border border-gray-100">
            {{ $story->type }}
        </div>
    </div>
    <div class="p-8 flex-1 flex flex-col bg-white">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('success-stories.show', $story->id) }}" class="shrink-0">
                @if(!empty($story->avatar_path))
                    <img src="{{ asset('storage/' . $story->avatar_path) }}" 
                         alt="{{ $story->name }}" 
                         class="w-12 h-12 rounded-full border-2 border-white shadow-md object-cover transition-transform hover:scale-105" 
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($story->name) }}&color=7F9CF5&background=EBF4FF';">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($story->name) }}&color=7F9CF5&background=EBF4FF" 
                         alt="{{ $story->name }}" 
                         class="w-12 h-12 rounded-full border-2 border-white shadow-md object-cover transition-transform hover:scale-105">
                @endif
            </a>
            <div>
                <a href="{{ route('success-stories.show', $story->id) }}" class="hover:underline decoration-indigo-500 decoration-2 underline-offset-2">
                    <h3 class="font-bold text-gray-900 line-clamp-1" title="{{ $story->name }}">{{ $story->name }}</h3>
                </a>
                <p class="text-sm text-gray-500 line-clamp-1" title="{{ $story->role }}">{{ $story->role }}</p>
            </div>
        </div>
        <blockquote class="text-gray-600 italic mb-6 flex-1 relative min-h-[4.5rem]">
            <span class="absolute top-0 left-0 -mt-2 -ml-2 text-4xl text-indigo-100 font-serif leading-none">"</span>
            <span class="relative z-10 line-clamp-3">{{ $story->quote }}</span>
        </blockquote>
        <div class="border-t border-gray-100 pt-6 mt-auto">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Service: <strong>{{ $story->service_category }}</strong></span>
                @if($story->story_content)
                    <a href="{{ route('success-stories.show', $story->id) }}" class="text-indigo-600 font-semibold hover:text-indigo-700 group inline-flex items-center">
                        Read Story <i class="fas fa-arrow-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
