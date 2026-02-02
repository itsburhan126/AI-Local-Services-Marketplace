@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Trust & Safety Item</h1>
            <p class="text-slate-500 text-sm mt-1">Update item details</p>
        </div>
        <a href="{{ route('admin.trust-safety.index') }}" class="text-slate-500 hover:text-indigo-600 font-bold text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-4xl">
        <form action="{{ route('admin.trust-safety.update', $trustSafety) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ $trustSafety->title }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Icon (Emoji)</label>
                    <input type="text" name="icon" value="{{ $trustSafety->icon }}" placeholder="e.g. 🛡️" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Background Color (Tailwind)</label>
                    <input type="text" name="bg_color" value="{{ $trustSafety->bg_color }}" placeholder="e.g. emerald-100" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Text Color (Tailwind)</label>
                    <input type="text" name="text_color" value="{{ $trustSafety->text_color }}" placeholder="e.g. emerald-600" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Order</label>
                    <input type="number" name="order" value="{{ $trustSafety->order }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ $trustSafety->description }}</textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $trustSafety->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-bold text-slate-700">Active</span>
                </label>
            </div>

            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                Update Item
            </button>
        </form>
    </div>
</div>
@endsection
