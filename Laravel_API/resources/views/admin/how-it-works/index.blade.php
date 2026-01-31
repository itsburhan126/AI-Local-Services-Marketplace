@extends('layouts.admin')

@section('title', 'How It Works Steps')

@section('content')
<div class="content-transition">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-jakarta">How It Works Steps</h1>
            <p class="text-gray-500 mt-1">Manage the steps shown on the How It Works page</p>
        </div>
        <button onclick="openCreateModal()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all flex items-center gap-2 group">
            <i class="fas fa-plus transition-transform group-hover:rotate-180"></i> Add Step
        </button>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 border border-emerald-100">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-6 border border-red-100">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="glass-panel rounded-2xl p-6 relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-4">Order</th>
                        <th class="px-4 py-4">Icon</th>
                        <th class="px-4 py-4">Title & Description</th>
                        <th class="px-4 py-4">Type</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($steps as $step)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                        <td class="px-4 py-4 font-semibold text-gray-600">
                            {{ $step->step_order }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="{{ $step->icon }}"></i>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $step->title }}</p>
                                <p class="text-sm text-gray-500 line-clamp-1">{{ $step->description }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-3 py-1 rounded-lg {{ $step->type === 'client' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }} text-sm font-semibold capitalize">
                                {{ $step->type }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal({{ json_encode($step) }})" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-white border border-gray-100 shadow-sm hover:shadow-md hover:text-indigo-600 transition-all">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.how-it-works-steps.destroy', $step->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this step?');">
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
                                <i class="fas fa-list-ol text-3xl text-gray-300"></i>
                                <p>No steps found. Add one to get started!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCreateModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.how-it-works-steps.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-gray-900">Add New Step</h3>
                    <p class="text-sm text-gray-500">Create a new step for the How It Works page.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="client">Client</option>
                            <option value="freelancer">Freelancer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Create an Account">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Step description..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class (FontAwesome)</label>
                            <input type="text" name="icon" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="fas fa-user">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                            <input type="number" name="step_order" value="1" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-medium transition-colors">Create Step</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-gray-900">Edit Step</h3>
                    <p class="text-sm text-gray-500">Update step details.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="edit_type" name="type" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="client">Client</option>
                            <option value="freelancer">Freelancer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="edit_title" name="title" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit_description" name="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class (FontAwesome)</label>
                            <input type="text" id="edit_icon" name="icon" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                            <input type="number" id="edit_step_order" name="step_order" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-medium transition-colors">Update Step</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(step) {
        document.getElementById('editForm').action = "/admin/how-it-works-steps/" + step.id;
        document.getElementById('edit_type').value = step.type;
        document.getElementById('edit_title').value = step.title;
        document.getElementById('edit_description').value = step.description;
        document.getElementById('edit_icon').value = step.icon;
        document.getElementById('edit_step_order').value = step.step_order;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
