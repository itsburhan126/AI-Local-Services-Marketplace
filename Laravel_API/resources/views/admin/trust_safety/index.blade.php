@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Trust & Safety</h1>
            <p class="text-slate-500 text-sm mt-1">Manage trust and safety items</p>
        </div>
        <a href="{{ route('admin.trust-safety.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Add Item
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase text-slate-500 font-bold tracking-wider">
                    <th class="p-4">Icon</th>
                    <th class="p-4">Title</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Colors</th>
                    <th class="p-4">Order</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($items as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg bg-{{ $item->bg_color }} text-{{ $item->text_color }}">
                            <i class="{{ $item->icon }}"></i>
                        </div>
                    </td>
                    <td class="p-4 font-bold text-slate-700">{{ $item->title }}</td>
                    <td class="p-4 text-slate-600 max-w-xs truncate">{{ $item->description }}</td>
                    <td class="p-4 text-xs">
                        <span class="block text-slate-500">Bg: {{ $item->bg_color }}</span>
                        <span class="block text-slate-500">Text: {{ $item->text_color }}</span>
                    </td>
                    <td class="p-4 text-slate-600">{{ $item->order }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $item->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-100' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.trust-safety.edit', $item) }}" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.trust-safety.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
        <div class="p-4 border-t border-slate-200">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
