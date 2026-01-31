@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Quality Guidelines</h1>
            <p class="text-slate-500 text-sm mt-1">Manage quality guidelines content</p>
        </div>
        <a href="{{ route('admin.quality-guidelines.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Add Guideline
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold tracking-wider">
                    <th class="p-4">Sort</th>
                    <th class="p-4">Title</th>
                    <th class="p-4">Color</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($guidelines as $guideline)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 text-slate-600 font-bold">{{ $guideline->sort_order }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-{{ $guideline->color_class }}-100 text-{{ $guideline->color_class }}-600 flex items-center justify-center">
                                <i class="{{ $guideline->icon_class }}"></i>
                            </div>
                            <span class="font-bold text-slate-700">{{ $guideline->title }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-{{ $guideline->color_class }}-50 text-{{ $guideline->color_class }}-600 border border-{{ $guideline->color_class }}-100">
                            {{ ucfirst($guideline->color_class) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $guideline->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-100' }}">
                            {{ $guideline->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.quality-guidelines.edit', $guideline) }}" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.quality-guidelines.destroy', $guideline) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
