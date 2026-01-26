@extends('layouts.admin')

@section('title', 'Service Types')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Service Types</h1>
            <p class="text-gray-500 mt-1">Manage service types for freelancer gigs</p>
        </div>
        <a href="{{ route('admin.service_types.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Add Service Type
        </a>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Name</th>
                        <th class="px-4 py-4">Slug</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($serviceTypes as $serviceType)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <span class="font-semibold text-gray-800">{{ $serviceType->name }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-gray-500 font-mono text-sm">{{ $serviceType->slug }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if($serviceType->is_active)
                                <span class="px-3 py-1 rounded-lg bg-green-50 text-green-700 text-sm font-semibold">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.service_types.edit', $serviceType->id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.service_types.destroy', $serviceType->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-tags text-3xl text-gray-300"></i>
                                <p>No service types found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $serviceTypes->links() }}
        </div>
    </div>
</div>
@endsection
