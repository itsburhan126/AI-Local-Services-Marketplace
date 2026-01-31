@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Guide</h1>
        <p class="text-slate-500 text-sm mt-1">Update guide details</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl">
        <form action="{{ route('admin.guides.update', $guide->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ old('title', $guide->title) }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Category</label>
                    <select name="category" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="General" {{ $guide->category == 'General' ? 'selected' : '' }}>General</option>
                        <option value="Freelancer" {{ $guide->category == 'Freelancer' ? 'selected' : '' }}>Freelancer</option>
                        <option value="Client" {{ $guide->category == 'Client' ? 'selected' : '' }}>Client</option>
                        <option value="Platform" {{ $guide->category == 'Platform' ? 'selected' : '' }}>Platform</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="is_active" value="1" {{ $guide->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-slate-600">Active</span>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Excerpt (Short Description)</label>
                    <textarea name="excerpt" rows="3" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('excerpt', $guide->excerpt) }}</textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Content</label>
                    <textarea name="content" rows="10" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content', $guide->content) }}</textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Cover Image</label>
                    @if($guide->image_path)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $guide->image_path) }}" alt="Current Image" class="h-32 rounded-lg object-cover">
                        </div>
                    @endif
                    <input type="file" name="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.guides.index') }}" class="px-6 py-2 rounded-lg border border-slate-300 text-slate-700 font-bold hover:bg-slate-50 transition-all">Cancel</a>
                <button type="submit" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Update Guide</button>
            </div>
        </form>
    </div>
</div>
@endsection
