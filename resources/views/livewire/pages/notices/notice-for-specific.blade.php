<div>
    <!-- Header Section -->
    <div class="flex flex-row flex-wrap gap-4 items-center p-2 m-6 mb-0 shadow">
        @can('Send Notice')
            <button type="button" class="btn btn-outline btn-primary btn-lg mt-5" wire:click="popUp">
                Send Notice to Specific Employees
            </button>
        @endcan
    </div>

    <!-- Modal Section -->
    <dialog class="modal" {{ $showModal ? 'open' : '' }}>
        <div class="modal-box max-w-lg">
            <form wire:submit.prevent="submit">
                <h3 class="font-bold text-lg mb-4">{{ $editingId ? 'Edit' : 'Send' }} Notice to Specific Employees</h3>
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
                <div class="mb-4">
                    <label class="label">Select Employees</label>
                    <select wire:model.defer="selectedUsers" class="select select-bordered w-full" multiple
                        size="6">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUsers')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-action mt-6 flex gap-2">
                    <button type="button" class="btn btn-outline" wire:click="popUpHide">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Send' }}</button>
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
                <h2 class="card-title">Notices for Specific Employees</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Employees</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(Auth::user()->hasRole(['Admin','Manager']))
                            @foreach ($notices as $notice)
                                <tr>
                                    <td>{{ $notice->title }}</td>
                                    <td>{{ $notice->content }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($notice->users as $user)
                                                <span class="badge badge-info">{{ $user->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>{{ $notice->created_at->format('d M, Y H:i') }}</td>
                                    <td>
                                        <div class="flex gap-1">
                                            @can('Send Notice')
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="edit({{ $notice->id }})">Edit</button>
                                            <button class="btn btn-sm btn-error"
                                                wire:click="delete({{ $notice->id }})">Delete</button>
                                                @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                            @if(Auth::user()->hasRole(['Web Designer','Digital Marketer','SEO Specialist','Customer Support']))
 {{-- {{ dd($notices) }} --}}
 @foreach ($notices as $notice)
 <tr>
     <td>{{ $notice->notice->title }}</td>
     <td>{{ $notice->notice->content }}</td>
     <td>
         <div class="flex flex-wrap gap-1">
             {{-- @foreach ($notice->user as $user) --}}
                 <span class="badge badge-info">{{ $notice->user->name }}</span>
             {{-- @endforeach --}}
         </div>
     </td>
     <td>{{ $notice->notice->created_at->format('d M, Y H:i') }}</td>
     <td>
         <div class="flex gap-1">
             @can('Send Notice')
             <button class="btn btn-sm btn-primary"
                 wire:click="edit({{ $notice->id }})">Edit</button>
             <button class="btn btn-sm btn-error"
                 wire:click="delete({{ $notice->id }})">Delete</button>
                 @endcan
         </div>
     </td>
 </tr>
@endforeach
@endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
