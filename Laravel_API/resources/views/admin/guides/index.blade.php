@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Guides</h1>
            <p class="text-slate-500 text-sm mt-1">Manage platform guides and articles</p>
        </div>
        <a href="{{ route('admin.guides.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Create Guide
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold tracking-wide text-slate-500 uppercase">
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($guides as $guide)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($guide->image_path)
                                <img src="{{ asset('storage/' . $guide->image_path) }}" alt="{{ $guide->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $guide->title }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-bold uppercase">{{ $guide->category }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold uppercase {{ $guide->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                {{ $guide->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-sm">{{ $guide->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.guides.edit', $guide->id) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-indigo-600 hover:border-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.guides.destroy', $guide->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-600 transition-all">
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
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $guides->links() }}
        </div>
    </div>
</div>
@endsection
