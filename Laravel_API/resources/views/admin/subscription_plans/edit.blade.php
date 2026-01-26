@extends('layouts.admin')

@section('title', 'Edit Subscription Plan')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.subscription-plans.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Edit Plan</h1>
            <p class="text-gray-500 mt-1">Update subscription package</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-3xl">
        <form action="{{ route('admin.subscription-plans.update', $subscriptionPlan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Plan Name</label>
                    <input type="text" name="name" value="{{ old('name', $subscriptionPlan->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Price ($)</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $subscriptionPlan->price) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Duration -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Duration (Days)</label>
                    <input type="number" name="duration_days" min="1" value="{{ old('duration_days', $subscriptionPlan->duration_days) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    @error('duration_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Features -->
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Features</label>
                <div id="features-container" class="space-y-3">
                    @if($subscriptionPlan->features)
                        @foreach($subscriptionPlan->features as $feature)
                        <div class="flex gap-2">
                            <input type="text" name="features[]" value="{{ $feature }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                            <button type="button" onclick="removeFeature(this)" class="w-12 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="flex gap-2">
                            <input type="text" name="features[]" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50" placeholder="Feature description">
                            <button type="button" onclick="removeFeature(this)" class="w-12 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addFeature()" class="mt-2 text-sm text-indigo-600 font-medium hover:text-indigo-800 flex items-center gap-1">
                    <i class="fas fa-plus-circle"></i> Add another feature
                </button>
            </div>

            <!-- Toggles -->
            <div class="flex gap-8 pt-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" {{ $subscriptionPlan->is_active ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <span class="text-gray-700 font-medium group-hover:text-indigo-600 transition-colors">Active Plan</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_featured" value="1" {{ $subscriptionPlan->is_featured ? 'checked' : '' }} class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <span class="text-gray-700 font-medium group-hover:text-indigo-600 transition-colors">Featured Plan</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Update Plan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50" placeholder="Feature description">
        <button type="button" onclick="removeFeature(this)" class="w-12 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(btn) {
    btn.parentElement.remove();
}
</script>
@endsection
