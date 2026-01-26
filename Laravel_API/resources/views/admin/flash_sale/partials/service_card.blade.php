<div class="service-card-item group relative bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer"
    data-search="{{ $service->name }} {{ $service->category->name ?? '' }}"
    @click="selected.includes({{ $service->id }}) ? selected = selected.filter(i => i !== {{ $service->id }}) : selected.push({{ $service->id }})"
    :class="selected.includes({{ $service->id }}) ? 'ring-2 ring-indigo-500 border-indigo-500 transform scale-[1.02]' : ''"
>
    <!-- Selection Checkbox -->
    <div class="absolute top-3 right-3 z-10 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
        :class="selected.includes({{ $service->id }}) ? 'bg-indigo-500 border-indigo-500' : 'bg-white/80 border-slate-300'"
    >
        <i class="fas fa-check text-white text-xs" x-show="selected.includes({{ $service->id }})"></i>
    </div>

    <!-- Image -->
            <div class="h-40 bg-slate-100 relative overflow-hidden">
                @if($service->image)
                    @if(str_starts_with($service->image, 'http'))
                        <img src="{{ $service->image }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <img src="{{ asset('storage/'.$service->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @endif
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                @if(isset($request) && $type === 'request')
                    <div class="absolute bottom-2 left-2 bg-indigo-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                        Proposed: {{ $request->proposed_discount }}%
                    </div>
                @endif
            </div>

    <!-- Content -->
    <div class="p-4">
        <div class="flex items-center gap-2 mb-2">
            @if($service->provider && $service->provider->avatar)
                <img src="{{ asset('storage/'.$service->provider->avatar) }}" class="w-5 h-5 rounded-full object-cover border border-slate-200">
            @else
                <div class="w-5 h-5 rounded-full bg-slate-200"></div>
            @endif
            <span class="text-xs text-slate-500 truncate">{{ $service->provider->name ?? 'Provider' }}</span>
        </div>
        
        <h4 class="font-bold text-slate-800 leading-tight mb-1 line-clamp-2 h-10">{{ $service->name }}</h4>
        <div class="flex items-center justify-between mt-2">
            <p class="text-indigo-600 font-bold text-sm">${{ $service->price }}</p>
            @if(isset($service->category))
            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded-full">{{ $service->category->name }}</span>
            @endif
        </div>
    </div>
</div>