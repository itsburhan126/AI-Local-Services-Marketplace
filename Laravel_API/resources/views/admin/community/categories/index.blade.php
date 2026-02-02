@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Community Categories</h1>
            <p class="text-slate-500 text-sm mt-1">Manage structure</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Slug</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Order</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-slate-700">{{ $category->name }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ $category->description }}</div>
                            </td>
                            <td class="p-4 text-sm text-slate-600">{{ $category->slug }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $category->type === 'forum' ? 'bg-blue-100 text-blue-700' : ($category->type === 'event' ? 'bg-pink-100 text-pink-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ ucfirst($category->type) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-slate-600">{{ $category->order }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
