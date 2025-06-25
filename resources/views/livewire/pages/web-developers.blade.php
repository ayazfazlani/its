<div wire:ignore.self>
    <section class="w-full p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Web Developers</h1>
                <p class="text-gray-500">Create, edit, and manage web development projects.</p>
            </div>
            <div class="flex gap-2">
                @can('Create Websites')
                    <button class="btn btn-primary" wire:click="popUp">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Website
                    </button>
                @endcan
            </div>
        </div>

        <!-- Modal -->
        <dialog id="website-modal" class="modal" @if ($showModal) open @endif>
            <form method="dialog" class="modal-box w-full max-w-2xl"
                wire:submit.prevent="{{ $editingId ? 'update' : 'save' }}">
                <h3 class="font-bold text-lg mb-4">{{ $editingId ? 'Update' : 'Add' }} Website</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="label">Project Name</label>
                        <input type="text" wire:model.defer="projectName" class="input input-bordered w-full"
                            placeholder="Enter project name" />
                        @error('projectName')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">Employee</label>
                        <select wire:model.defer="employeeId" class="select select-bordered w-full">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                            @endforeach
                        </select>
                        @error('employeeId')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">Website URL</label>
                        <input type="url" wire:model.defer="websiteUrl" class="input input-bordered w-full"
                            placeholder="Enter website URL" />
                        @error('websiteUrl')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">Category</label>
                        <select wire:model.defer="category" class="select select-bordered w-full">
                            <option value="">Select Category</option>
                            <option value="Business">Business</option>
                            <option value="E-Commerce">E-Commerce</option>
                            <option value="Portfolio">Portfolio</option>
                            <option value="Blog">Blog</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('category')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">Status</label>
                        <select wire:model.defer="status" class="select select-bordered w-full">
                            <option value="">Select Status</option>
                            <option value="in progress">In Progress</option>
                            <option value="in review">In Review</option>
                            <option value="delivered">Completed</option>
                            <option value="delayed">Delayed</option>
                        </select>
                        @error('status')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">Tools Used</label>
                        <select wire:model.defer="toolsUsed" class="select select-bordered w-full">
                            <option value="">Select Tools</option>
                            <option value="wordpress">Wordpress</option>
                            <option value="shopify">Shopify</option>
                            <option value="coding">Coding</option>
                        </select>
                        @error('toolsUsed')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">Start Date</label>
                        <input type="date" wire:model.defer="startDate" class="input input-bordered w-full" />
                        @error('startDate')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="label">End Date</label>
                        <input type="date" wire:model.defer="endDate" class="input input-bordered w-full" />
                        @error('endDate')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label class="label">Description</label>
                        <textarea wire:model.defer="description" class="textarea textarea-bordered w-full" rows="3"
                            placeholder="Enter project description"></textarea>
                        @error('description')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($status === 'cancelled')
                        <div class="mb-4 md:col-span-2">
                            <label class="label">Reason</label>
                            <textarea wire:model.defer="reason" class="textarea textarea-bordered w-full" rows="3"
                                placeholder="Enter reason for cancellation"></textarea>
                            @error('reason')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
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

        <!-- Website Table Section -->
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Employee</th>
                        <th>Category</th>
                        <th>Website URL</th>
                        <th>Tools Used</th>
                        <th>Performance</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($websites as $website)
                        <tr>
                            <td>{{ $website->project_name }}</td>
                            <td>{{ $website->employee->user->name }}</td>
                            <td>{{ $website->category }}</td>
                            <td>
                                @if ($website->website_url)
                                    <a target="_blank"
                                        href="{{ $website->website_url }}">{{ Str::limit($website->website_url, 30) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ Str::limit($website->tools_used, 20) }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <progress class="progress progress-success w-24"
                                        value="{{ $website->performance }}" max="100"></progress>
                                    <span>{{ number_format($website->performance, 1) }}%</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($website->start_date)->format('d M, Y') }}</td>
                            <td>{{ $website->end_date ? \Carbon\Carbon::parse($website->end_date)->format('d M, Y') : '-' }}
                            </td>
                            <td>
                                <span
                                    class="badge {{ $website->status === 'completed'
                                        ? 'badge-success'
                                        : ($website->status === 'in_progress'
                                            ? 'badge-primary'
                                            : ($website->status === 'paused'
                                                ? 'badge-warning'
                                                : 'badge-error')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $website->status)) }}
                                </span>
                            </td>
                            <td class="text-right space-x-2">
                                @can('Edit Websites')
                                    <button class="btn btn-sm btn-outline"
                                        wire:click="edit({{ $website->id }})">Edit</button>
                                @endcan
                                @can('Delete Websites')
                                    <button class="btn btn-sm btn-error"
                                        wire:click="delete({{ $website->id }})">Delete</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
