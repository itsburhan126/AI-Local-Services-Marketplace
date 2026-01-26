@extends('layouts.admin')

@section('title', 'Send Notification')

@section('content')
<div class="content-transition">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.push-notifications.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:shadow-md transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Send Notification</h1>
            <p class="text-gray-500 mt-1">Compose a new push message</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl p-8 max-w-3xl">
        <form action="{{ route('admin.push-notifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Notification Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50"
                    placeholder="e.g. Special Offer Inside!">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Message Body</label>
                <textarea name="body" rows="4" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50 resize-none"
                    placeholder="Write your message here...">{{ old('body') }}</textarea>
                @error('body') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Target Audience</label>
                    <select name="target_audience" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                        <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="customer" {{ old('target_audience') == 'customer' ? 'selected' : '' }}>Customers Only</option>
                        <option value="provider" {{ old('target_audience') == 'provider' ? 'selected' : '' }}>Providers Only</option>
                    </select>
                    @error('target_audience') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Schedule (Optional)</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all bg-white/50">
                    <p class="text-xs text-gray-400">Leave empty to send immediately</p>
                    @error('scheduled_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-700">Image (Optional)</label>
                <div class="relative group">
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                </div>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i> Send Notification
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
