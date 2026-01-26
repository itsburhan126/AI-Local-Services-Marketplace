@extends('layouts.admin')

@section('title', 'Zones')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">Operational Zones</h1>
            <p class="text-gray-500 mt-1">Manage service areas and cities</p>
        </div>
        <a href="{{ route('admin.zones.create') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Add Zone
        </a>
    </div>

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Zone Name</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Coordinates Preview</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($zones as $zone)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $zone->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $zone->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $zone->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-500 font-mono">
                            {{ Str::limit($zone->coordinates, 30) }}
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.zones.edit', $zone->id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                                <i class="fas fa-globe-americas text-3xl text-gray-300"></i>
                                <p>No zones defined</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 px-4">
            {{ $zones->links() }}
        </div>
    </div>
</div>
@endsection
