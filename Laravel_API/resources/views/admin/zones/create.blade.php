@extends('layouts.admin')

@section('title', 'Add Zone')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.zones.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Add Zone</h1>
            <p class="text-gray-500 mt-1">Define a new service area</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-3xl">
        <form action="{{ route('admin.zones.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Zone Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                    placeholder="e.g. New York City">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Coordinates (WKT/JSON)</label>
                <textarea name="coordinates" rows="6" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50 font-mono text-sm"
                    placeholder="Enter zone coordinates data...">{{ old('coordinates') }}</textarea>
                <p class="text-xs text-gray-400">Paste the coordinate data for the zone boundary.</p>
                @error('coordinates') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <span class="text-gray-700 font-medium group-hover:text-indigo-600 transition-colors">Active Zone</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    Create Zone
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
