<div class="{{ $cardWidth ?? 'w-full' }} bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 group cursor-pointer flex flex-col h-full relative overflow-hidden">
    <!-- Main Link (Covers entire card) -->
    <a href="{{ route('customer.gigs.show', $gig->slug ?? '#') }}" class="absolute inset-0 z-10" aria-label="View {{ $gig->title }}"></a>

    <!-- Image -->
    <div class="relative h-[200px] overflow-hidden">
        @php
            $cardImage = 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop';
            $thumb = $gig->thumbnail_image ?? $gig->image; // Try thumbnail first, then image
            
            if (!empty($thumb)) {
                if (filter_var($thumb, FILTER_VALIDATE_URL)) {
                    $parsed = parse_url($thumb, PHP_URL_PATH);
                    $rel = preg_replace('/^\/?storage\//', '', ltrim($parsed, '/'));
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rel)) {
                        $cardImage = $thumb;
                    }
                } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($thumb)) {
                    $cardImage = asset('storage/' . $thumb);
                }
            }
        @endphp
        <img src="{{ $cardImage }}" 
             alt="{{ $gig->title }}" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute top-3 right-3 z-20">
            <button class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center hover:bg-white text-gray-400 hover:text-red-500 transition-colors shadow-sm focus:outline-none" onclick="event.preventDefault(); /* Add favorite logic here */">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="p-4 flex flex-col flex-grow relative z-0">
        <!-- Seller Info -->
        <div class="flex items-center gap-2 mb-3 relative z-20">
            <a href="{{ route('customer.seller.profile', \Illuminate\Support\Str::slug($gig->provider->name ?? 'provider')) }}" class="flex items-center gap-2 flex-1 group/seller hover:opacity-80 transition-opacity">
                <div class="relative">
                    <img src="{{ $gig->provider ? $gig->provider->profile_photo_url : 'https://ui-avatars.com/api/?name=Provider&background=4F46E5&color=ffffff' }}" 
                         alt="Seller" 
                         class="w-6 h-6 rounded-full object-cover border border-gray-100">
                    @if(isset($gig->provider->is_online) && $gig->provider->is_online)
                        <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                    @endif
                </div>
                <span class="text-xs font-bold text-gray-900 truncate flex-1 group-hover/seller:text-emerald-600 transition-colors">{{ $gig->provider ? $gig->provider->name : 'Unknown Seller' }}</span>
            </a>
            <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-bold text-gray-900">{{ number_format($gig->rating ?? 0, 1) }}</span>
                <span class="text-xs text-gray-400">({{ $gig->reviews_count ?? 0 }})</span>
            </div>
        </div>

        <!-- Gig Title -->
        <h3 class="text-sm font-semibold text-gray-800 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">
            {{ $gig->title }}
        </h3>

        <!-- Footer: Price -->
        <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Starting at</span>
            <span class="text-lg font-bold text-gray-900">${{ $gig->packages->first() ? $gig->packages->first()->price : '0' }}</span>
        </div>
    </div>
</div>
