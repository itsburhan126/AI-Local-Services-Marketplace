@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Categories</h1>
            <p class="text-gray-500 mt-1">Manage service categories and hierarchy</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Add Category
        </a>
    </div>

    <div class="mb-6 flex gap-2">
        <a href="{{ route('admin.categories.index') }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('type') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
            All Categories
        </a>
        <a href="{{ route('admin.categories.index', ['type' => 'local_service']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('type') === 'local_service' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
            Local Services
        </a>
        <a href="{{ route('admin.categories.index', ['type' => 'freelancer']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('type') === 'freelancer' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
            Freelancer Services
        </a>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Parent</th>
                        <th class="px-4 py-4">Type</th>
                        <th class="px-4 py-4">Commission</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shadow-sm flex items-center justify-center">
                                    @if($category->image)
                                        <img src="{{ $category->image }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-layer-group text-gray-300 text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $category->name }}</p>
                                    <p class="text-xs text-gray-500">/{{ $category->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @if($category->parent)
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">
                                    {{ $category->parent->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm italic">Root Category</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-semibold">
                                {{ ($category->type ?? 'local_service') === 'freelancer' ? 'Freelancer' : 'Local Service' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-medium text-gray-700">{{ $category->commission_rate ?? '0' }}%</span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-red-600 transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                                <p>No categories found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
