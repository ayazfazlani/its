<div>
    <!-- Header Section -->
    <div class="flex flex-row flex-wrap gap-4 items-center p-2 m-6 mb-0 shadow">
        <button type="button" class="btn btn-outline btn-primary btn-lg mt-5" wire:click="popUp">
            Add Notice
        </button>
    </div>

    <!-- Modal Section -->
    <dialog class="modal" {{ $showModal ? 'open' : '' }}>
        <div class="modal-box max-w-lg">
            <form wire:submit.prevent="submit">
                <h3 class="font-bold text-lg mb-4">{{ $editingId ? 'Edit' : 'Add' }} Notice</h3>
                <div class="mb-4">
                    <label class="label">Title</label>
                    <input type="text" wire:model.defer="title" class="input input-bordered w-full"
                        placeholder="Enter notice title" />
                    @error('title')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Content</label>
                    <textarea wire:model.defer="content" class="textarea textarea-bordered w-full" placeholder="Enter notice content"
                        rows="4"></textarea>
                    @error('content')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-action mt-6 flex gap-2">
                    <button type="button" class="btn btn-outline" wire:click="popUpHide">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Add' }}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button wire:click="popUpHide">close</button>
        </form>
    </dialog>

    <!-- Notices List -->
    <div class="p-4">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">All Notices</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notices as $notice)
                                <tr>
                                    <td>{{ $notice->title }}</td>
                                    <td>{{ $notice->content }}</td>
                                    <td>{{ $notice->created_at->format('d M, Y H:i') }}</td>
                                    <td>
                                        @can('Send Notice')
                                        <div class="flex gap-1">
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="edit({{ $notice->id }})">Edit</button>
                                            <button class="btn btn-sm btn-error"
                                                wire:click="delete({{ $notice->id }})">Delete</button>
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
