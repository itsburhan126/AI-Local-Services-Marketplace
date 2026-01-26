@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Gig Details</h1>
            <p class="text-slate-500 text-sm mt-1">Review gig details before approval</p>
        </div>
        <a href="{{ route('admin.gigs.requests') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-xl shadow-sm flex items-center gap-2 text-sm font-bold transition-all">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Basic Information</h2>
                
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Title</label>
                    <p class="text-xl font-bold text-slate-800">{{ $gig->title }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Category</label>
                        <span class="inline-block bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-sm font-bold border border-indigo-100">
                            {{ $gig->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Service Type</label>
                        <span class="inline-block bg-purple-50 text-purple-600 px-3 py-1 rounded-lg text-sm font-bold border border-purple-100">
                            {{ $gig->serviceType->name ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description</label>
                    <div class="prose prose-slate max-w-none text-slate-600 text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
                        {!! nl2br(e($gig->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Gallery</h2>
                
                @if($gig->thumbnail_image)
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-slate-600 mb-2">Thumbnail</h3>
                    <div class="relative group aspect-video w-full md:w-1/2 rounded-xl overflow-hidden shadow-sm border border-slate-100">
                        @php
                            $thumbnail = str_replace('http://localhost', url('/'), $gig->thumbnail_image);
                        @endphp
                        <img src="{{ $thumbnail }}" class="w-full h-full object-cover" alt="Gig Thumbnail">
                    </div>
                </div>
                @endif

                <h3 class="text-sm font-bold text-slate-600 mb-2">Images</h3>
                @if(!empty($gig->images))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($gig->images as $image)
                    @php
                        $imageUrl = str_replace('http://localhost', url('/'), $image);
                    @endphp
                    <div class="relative group aspect-square rounded-xl overflow-hidden shadow-sm border border-slate-100">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover transition-transform group-hover:scale-105" alt="Gig Image">
                        <a href="{{ $imageUrl }}" target="_blank" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-expand text-white text-xl"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-slate-400 italic">No images uploaded.</p>
                @endif
            </div>

            <!-- Packages -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Packages</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Delivery</th>
                                <th class="px-4 py-3">Revisions</th>
                                <th class="px-4 py-3">Features</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($gig->packages as $package)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-700 capitalize">{{ $package->type }}</td>
                                <td class="px-4 py-3 text-indigo-600 font-bold">${{ number_format($package->price, 2) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $package->delivery_time }} Days</td>
                                <td class="px-4 py-3 text-slate-600">{{ $package->revisions }}</td>
                                <td class="px-4 py-3 text-slate-500 text-xs">
                                    @php
                                        $features = is_string($package->features) ? json_decode($package->features, true) : $package->features;
                                    @endphp
                                    @if($features && is_array($features))
                                        <ul class="list-disc pl-4">
                                            @foreach($features as $feature)
                                                <li>{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Status</h2>
                
                <div class="mb-6">
                    <span class="w-full block text-center py-2 rounded-lg font-bold uppercase text-sm
                        {{ $gig->status === 'approved' ? 'bg-emerald-100 text-emerald-600' : '' }}
                        {{ $gig->status === 'pending' ? 'bg-amber-100 text-amber-600' : '' }}
                        {{ $gig->status === 'rejected' ? 'bg-red-100 text-red-600' : '' }}
                    ">
                        {{ $gig->status }}
                    </span>
                </div>

                @if($gig->status === 'pending')
                <div class="space-y-3">
                    <form action="{{ route('admin.gigs.approve', $gig->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> Approve Gig
                        </button>
                    </form>

                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> Reject Gig
                    </button>
                </div>
                @endif
            </div>

            <!-- Freelancer Info -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Freelancer</h2>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xl">
                        {{ substr($gig->provider->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $gig->provider->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $gig->provider->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.providers.show', $gig->provider->id) }}" class="block w-full text-center bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold py-2 rounded-lg text-sm transition-colors">
                    View Profile
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
        <div class="p-6">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Reject Gig</h3>
            <p class="text-slate-500 text-sm mb-4">Please provide a reason for rejection. This will be sent to the freelancer.</p>
            
            <form action="{{ route('admin.gigs.reject', $gig->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Rejection Reason</label>
                    <textarea name="admin_note" rows="4" class="w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="e.g., Poor image quality, vague description..." required></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-red-200 transition-colors">
                        Reject Gig
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
