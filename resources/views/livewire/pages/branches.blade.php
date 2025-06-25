<section class="w-full p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Branches Management</h1>
            <p class="text-gray-500">Create, edit, and delete branches.</p>
        </div>
        <div class="flex gap-2">
            @can('Ad Branches')
                <button class="btn btn-primary" wire:click="popUp">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Branch
                </button>
            @endcan
        </div>
    </div>

    <!-- Modal -->
    <dialog id="branch-modal" class="modal" @if ($showModal ?? false) open @endif>
        <form method="dialog" class="modal-box w-full max-w-md"
            wire:submit.prevent="{{ $branchId ? 'update' : 'save' }}">
            <h3 class="font-bold text-lg mb-4">{{ $branchId ? 'Update' : 'Add' }} Branch</h3>
            <div class="mb-4">
                <label class="label">Branch Name</label>
                <input type="text" wire:model.defer="name" class="input input-bordered w-full"
                    placeholder="Enter branch name here" />
                @error('name')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="label">Branch Location</label>
                <input type="text" wire:model.defer="location" class="input input-bordered w-full"
                    placeholder="Enter branch location here" />
                @error('location')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
            <div class="modal-action flex gap-2">
                <button type="button" class="btn" wire:click="popUpHide">Cancel</button>
                <button type="submit" class="btn btn-primary">{{ $branchId ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </dialog>

    <!-- Branch Table Section -->
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branch)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->location }}</td>
                        <td class="text-right space-x-2">
                            @can('Edit Branches')
                                <button class="btn btn-sm btn-outline" wire:click="edit({{ $branch->id }})">Edit</button>
                            @endcan
                            @can('Delete Branches')
                                <button class="btn btn-sm btn-error"
                                    wire:click="delete({{ $branch->id }})">Delete</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-400 py-6">No branches found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
