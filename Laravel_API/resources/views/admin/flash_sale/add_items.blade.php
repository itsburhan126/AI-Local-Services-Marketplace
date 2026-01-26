@extends('layouts.admin')

@section('header', 'Add Items')

@section('content')
<div class="space-y-8 pb-32" x-data="{ activeTab: 'top', selected: [], discount: {{ $flashSale->default_discount_percentage }} }">
    
    <!-- Professional Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.flash-sale.index') }}" class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:scale-105 transition-all group">
                <i class="fas fa-arrow-left text-slate-400 group-hover:text-slate-600"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Add Services to Flash Sale</h2>
                <div class="flex items-center gap-2 text-sm text-slate-500 font-medium">
                    <span>Campaign Management</span>
                    <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                    <span class="text-indigo-600">Selection</span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
            <span class="px-3 py-1.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Selection Mode</span>
            <div class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                Multi-Select Active
            </div>
        </div>
    </div>

    <!-- Tabs & Content -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden min-h-[600px]">
        <!-- Custom Tabs -->
        <div class="flex items-center gap-1 p-2 bg-slate-50/50 border-b border-slate-100 overflow-x-auto">
            <button @click="activeTab = 'top'" 
                :class="activeTab === 'top' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-all whitespace-nowrap text-sm">
                <i class="fas fa-trophy" :class="activeTab === 'top' ? 'text-yellow-500' : 'text-slate-400'"></i>
                Top Performing
            </button>
            <button @click="activeTab = 'requested'" 
                :class="activeTab === 'requested' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-all whitespace-nowrap text-sm relative">
                <i class="fas fa-clipboard-list" :class="activeTab === 'requested' ? 'text-indigo-500' : 'text-slate-400'"></i>
                Requested Services
                @if($requestedServices->count() > 0)
                <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
                @endif
            </button>
            <button @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'" 
                class="flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-all whitespace-nowrap text-sm">
                <i class="fas fa-th-large" :class="activeTab === 'all' ? 'text-indigo-500' : 'text-slate-400'"></i>
                All Services
            </button>
        </div>

        <div class="p-6 md:p-8 bg-slate-50/30">
            <!-- Top Services Tab -->
            <div x-show="activeTab === 'top'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @foreach($topServices as $service)
                    @include('admin.flash_sale.partials.service_card', ['service' => $service, 'type' => 'top'])
                @endforeach
                @if($topServices->isEmpty())
                     <div class="col-span-full py-20 text-center text-slate-400 flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <i class="fas fa-trophy text-3xl opacity-30"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600">No Recommendations Yet</h3>
                        <p class="text-sm">We need more booking data to recommend top services.</p>
                    </div>
                @endif
            </div>

            <!-- Requested Services Tab -->
            <div x-show="activeTab === 'requested'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @forelse($requestedServices as $req)
                    @include('admin.flash_sale.partials.service_card', ['service' => $req->service, 'request' => $req, 'type' => 'request'])
                @empty
                    <div class="col-span-full py-20 text-center text-slate-400 flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <i class="fas fa-inbox text-3xl opacity-30"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600">No Requests</h3>
                        <p class="text-sm">No providers have requested to join the flash sale yet.</p>
                    </div>
                @endforelse
            </div>

            <!-- All Services Tab -->
            <div x-show="activeTab === 'all'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="relative max-w-lg">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Search by service name..." class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all shadow-sm" onkeyup="filterServices(this.value)">
                </div>
                <div id="all-services-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                     @foreach($allServices as $service)
                        @include('admin.flash_sale.partials.service_card', ['service' => $service, 'type' => 'all'])
                    @endforeach
                    @if($allServices->isEmpty())
                        <div class="col-span-full py-20 text-center text-slate-400 flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <i class="fas fa-box-open text-3xl opacity-30"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-600">No Services Available</h3>
                            <p class="text-sm">All active services are already in the Flash Sale!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Floating Action Bar -->
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[95%] max-w-4xl bg-slate-900/90 backdrop-blur-xl border border-white/10 p-4 rounded-2xl z-50 shadow-2xl transition-all duration-300 transform"
         x-show="selected.length > 0"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-20 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-20 scale-95">
         
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-white">
                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold shadow-lg shadow-indigo-500/50" x-text="selected.length">0</div>
                <div>
                    <p class="font-bold text-sm">Services Selected</p>
                    <p class="text-xs text-slate-400">Ready to be added to campaign</p>
                </div>
            </div>

            <div class="h-8 w-px bg-white/10 hidden md:block"></div>

            <div class="flex items-center gap-3">
                <label class="text-xs font-bold text-slate-300 uppercase tracking-wider">Discount</label>
                <div class="relative">
                    <input type="number" x-model="discount" min="0" max="100" class="w-20 px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white text-center font-bold focus:ring-indigo-500 focus:border-indigo-500 placeholder-white/50">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                </div>
            </div>

            <form action="{{ route('admin.flash-sale.store-item') }}" method="POST" enctype="multipart/form-data" class="flex-1 md:flex-none">
                @csrf
                <template x-for="id in selected">
                    <input type="hidden" name="service_ids[]" :value="id">
                </template>
                <input type="hidden" name="discount_percentage" :value="discount">
                
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <i class="fas fa-bolt"></i>
                    <span>Launch Deals</span>
                </button>
            </form>
        </div>
    </div>

</div>

<!-- AlpineJS for Tab & Selection Logic -->
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    function filterServices(query) {
        query = query.toLowerCase();
        document.querySelectorAll('#all-services-grid .service-card-item').forEach(card => {
            const text = card.getAttribute('data-search').toLowerCase();
            card.style.display = text.includes(query) ? 'block' : 'none';
        });
    }
</script>
@endsection
