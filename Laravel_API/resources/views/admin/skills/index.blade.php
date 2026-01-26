@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Skills</h1>
            <p class="text-slate-500 text-sm mt-1">Manage freelancer skills</p>
        </div>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-lg shadow-indigo-200 flex items-center gap-2 text-sm font-bold transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Add Skill
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl border border-emerald-100 flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <div class="glass-panel rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-left">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($skills as $skill)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $skill->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $skill->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-600' }}">
                                {{ $skill->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button onclick="editSkill({{ $skill->id }}, '{{ $skill->name }}', {{ $skill->is_active }})" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 hover:bg-indigo-100 flex items-center justify-center transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $skills->links() }}
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-slate-800">Add Skill</h3>
            <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.skills.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked id="create_is_active" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                    <label for="create_is_active" class="text-sm font-medium text-slate-700">Active</label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-colors">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-slate-800">Edit Skill</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                    <label for="edit_is_active" class="text-sm font-medium text-slate-700">Active</label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-colors">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editSkill(id, name, active) {
        document.getElementById('editForm').action = `/admin/skills/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_is_active').checked = active == 1;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
