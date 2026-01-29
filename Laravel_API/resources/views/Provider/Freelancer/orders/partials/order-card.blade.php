<div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
        <!-- Thumbnail -->
        <div class="shrink-0">
            @if($order->gig && $order->gig->thumbnail_image)
                <img src="{{ asset('storage/' . $order->gig->thumbnail_image) }}" alt="{{ $order->gig->title }}" class="w-full sm:w-32 h-32 object-cover rounded-lg">
            @elseif($order->service && $order->service->image)
                 <img src="{{ asset('storage/' . $order->service->image) }}" alt="{{ $order->service->name }}" class="w-full sm:w-32 h-32 object-cover rounded-lg">
            @else
                <div class="w-full sm:w-32 h-32 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">
                    <i class="fas fa-image text-2xl"></i>
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-2">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-medium text-slate-500">Order #{{ $order->id }}</span>
                        <span class="text-slate-300">•</span>
                        <span class="text-xs text-slate-500">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 line-clamp-1 group-hover:text-primary-600 transition-colors">
                        @if($order->gig)
                            {{ $order->gig->title }}
                        @elseif($order->service)
                            {{ $order->service->name }} (Service)
                        @else
                            Service Unavailable
                        @endif
                    </h3>
                </div>
                
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'accepted' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'in_progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'ready' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                        'refunded' => 'bg-red-50 text-red-700 border-red-200',
                    ];
                    $statusClass = $statusColors[$order->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            <div class="flex items-center gap-2 mb-4">
                @if($order->user)
                    <div class="h-6 w-6 rounded-full bg-slate-200 overflow-hidden">
                        <img src="{{ $order->user->avatar ? asset('storage/' . $order->user->avatar) : asset('images/default-avatar.png') }}" class="h-full w-full object-cover">
                    </div>
                    <span class="text-sm text-slate-600 font-medium">{{ $order->user->name }}</span>
                @else
                    <span class="text-sm text-slate-500 italic">Unknown User</span>
                @endif
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                <div class="flex items-center gap-4">
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-500">Price</span>
                        <span class="font-bold text-slate-800">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    @if($order->package)
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500">Package</span>
                            <span class="font-medium text-slate-800">{{ $order->package->name ?? ucfirst($order->package->tier ?? 'Custom') }}</span>
                        </div>
                    @endif
                </div>

                <a href="{{ route('provider.freelancer.orders.show', $order->id) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    View Details
                </a>
            </div>
        </div>
    </div>
</div>
