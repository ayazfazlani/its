<div wire:ignore.self>
    <section class="w-full p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Client Left Advertisements</h1>
                <p class="text-gray-500">Manage and track advertisements where the client left.</p>
            </div>
            <div class="flex gap-2">
                @can('Create Ads')
                    <button class="btn btn-primary" wire:click="popUp">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Advertisement
                    </button>
                @endcan
            </div>
        </div>
        <!-- Modal -->
        <dialog id="ad-modal" class="modal" @if ($editingId !== null || $showModal ?? false) open @endif>
            <form method="dialog" class="modal-box w-full max-w-2xl" wire:submit.prevent="save">
                <h3 class="font-bold text-lg mb-4">{{ $editingId ? 'Update' : 'Add' }} Advertisement</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Name</label>
                        <input type="text" wire:model.defer="name" class="input input-bordered w-full"
                            placeholder="Enter advertisement name" />
                        @error('name')
                            <span class="text-error text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Employee</label>
                        <select wire:model.defer="employeeId" class="select select-bordered w-full">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                            @endforeach
                        </select>
                        @error('employeeId')
                            <span class="text-error text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Web URL</label>
                        <input type="url" wire:model.defer="webUrl" class="input input-bordered w-full"
                            placeholder="Enter web URL" />
                        @error('webUrl')
                            <span class="text-error text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select wire:model.defer="status" class="select select-bordered w-full">
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="pause">Pause</option>
                            <option value="inActive">Overdue</option>
                            <option value="clientLeft">Client left</option>
                        </select>
                        @error('status')
                            <span class="text-error text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Start Date</label>
                        <input type="date" wire:model.defer="startDate" class="input input-bordered w-full" />
                        @error('startDate')
                            <span class="text-error text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="label">End Date</label>
                        <input type="date" wire:model.defer="endDate" class="input input-bordered w-full" />
                        @error('endDate')
                            <span class="text-error text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($status === 'pause' || $status === 'clientLeft')
                        <div class="md:col-span-2">
                            <label class="label">Reason</label>
                            <textarea wire:model.defer="reason" class="textarea textarea-bordered w-full"
                                placeholder="Enter reason for inactive status" rows="3"></textarea>
                            @error('reason')
                                <span class="text-error text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="modal-action flex gap-2">
                    <button type="button" class="btn" wire:click="popUpHide">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </dialog>
        <!-- Advertisement Table Section -->
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Ad Name</th>
                        <th>Employee</th>
                        <th>Web URL</th>
                        <th>Performance</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($advertisements as $ad)
                        <tr>
                            <td @click="window.location='{{ route('ad.details', $ad->id) }}'" style="cursor:pointer;">
                                {{ $ad->name }}
                            </td>
                            <td @click="window.location='{{ route('ad.details', $ad->id) }}'" style="cursor:pointer;">
                                {{ $ad->employee->user->name }}
                            </td>
                            <td @click="window.location='{{ route('ad.details', $ad->id) }}'" style="cursor:pointer;">
                                <a target="_blank" href="{{ $ad->web_url }}">{{ $ad->web_url }}</a>
                            </td>
                            <td @click="window.location='{{ route('ad.details', $ad->id) }}'" style="cursor:pointer;">
                                <div class="flex items-center gap-2">
                                    <progress class="progress progress-success w-24" value="{{ $ad->performance }}"
                                        max="100"></progress>
                                    <span>{{ number_format($ad->performance, 1) }}%</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ad->start_date)->format('d M, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ad->end_date)->format('d M, Y') }}</td>
                            <td>{{ Str::limit($ad->reason, 30) }}</td>
                            <td>
                                @php
                                    $badgeClass = match ($ad->status) {
                                        'active' => 'badge-success',
                                        'pause' => 'badge-warning',
                                        'clientLeft' => 'badge-secondary',
                                        'inActive' => 'badge-error',
                                        'archived' => 'badge-info',
                                        default => 'badge-neutral',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ match ($ad->status) {
                                        'active' => 'Active',
                                        'pause' => 'Paused',
                                        'clientLeft' => 'Client Left',
                                        'inActive' => 'Overdue',
                                        'archived' => 'Archived',
                                        default => ucfirst($ad->status),
                                    } }}
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                @can('Edit Ads')
                                    <button class="btn btn-sm btn-outline"
                                        wire:click="edit({{ $ad->id }})">Edit</button>
                                @endcan
                                @can('Delete Ads')
                                    <button class="btn btn-sm btn-error"
                                        wire:click="delete({{ $ad->id }})">Delete</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
