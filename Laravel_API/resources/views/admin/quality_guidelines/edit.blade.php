@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Guideline</h1>
            <p class="text-slate-500 text-sm mt-1">Edit quality guideline section</p>
        </div>
        <a href="{{ route('admin.quality-guidelines.index') }}" class="text-slate-500 hover:text-indigo-600 font-bold text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-4xl">
        <form action="{{ route('admin.quality-guidelines.update', $qualityGuideline) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Title</label>
                    <input type="text" name="title" value="{{ $qualityGuideline->title }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $qualityGuideline->sort_order }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Icon Class (FontAwesome)</label>
                    <input type="text" name="icon_class" value="{{ $qualityGuideline->icon_class }}" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Color Class</label>
                    <select name="color_class" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach(['indigo', 'blue', 'emerald', 'purple', 'orange', 'pink', 'red'] as $color)
                            <option value="{{ $color }}" {{ $qualityGuideline->color_class == $color ? 'selected' : '' }}>{{ ucfirst($color) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description (HTML supported)</label>
                <textarea name="description" rows="6" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ $qualityGuideline->description }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Use HTML tags like &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt; for formatting.</p>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $qualityGuideline->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm font-bold text-slate-700">Active</span>
                </label>
            </div>

            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-700 px-6 py-2 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold transition-all transform hover:-translate-y-0.5">
                Update Guideline
            </button>
        </form>
    </div>
</div>
@endsection
