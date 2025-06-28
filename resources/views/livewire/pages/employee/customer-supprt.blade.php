<section class="w-full p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Customer Support Management</h1>
            <p class="text-gray-500">Create, edit, and delete customer support employees.</p>
        </div>
        <div class="flex gap-2">
            {{-- @can('Add Employee')
                <button class="btn btn-primary" wire:click="popUp">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Employee
                </button>
            @endcan --}}
        </div>
    </div>

    <!-- Modal -->
    <dialog id="employee-modal" class="modal" @if ($showModal) open @endif>
        <form method="dialog" class="modal-box w-full max-w-2xl"
            wire:submit.prevent="{{ $employeeId ? 'update' : 'create' }}">
            <h3 class="font-bold text-lg mb-4">{{ $employeeId ? 'Update' : 'Add' }} Employee</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="label">User Name</label>
                    <select wire:model.defer="userId" class="select select-bordered w-full">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('userId')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Branch</label>
                    <select wire:model.defer="branchId" class="select select-bordered w-full">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}-{{ $branch->location }}</option>
                        @endforeach
                    </select>
                    @error('branchId')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Department</label>
                    <select wire:model.defer="department" class="select select-bordered w-full">
                        <option value="">Select Department</option>
                        <option value="web design">Web Design | Development</option>
                        <option value="digital marketing">Digital Marketing</option>
                        <option value="seo">SEO</option>
                        <option value="customer support">Customer Support</option>
                        <option value="on_leave">Other</option>
                    </select>
                    @error('department')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Position</label>
                    <input type="text" wire:model.defer="position" class="input input-bordered w-full"
                        placeholder="Enter position" />
                    @error('position')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Phone</label>
                    <input type="text" wire:model.defer="phone" class="input input-bordered w-full"
                        placeholder="Enter phone number" />
                    @error('phone')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">CNIC</label>
                    <input type="text" wire:model.defer="cnic" class="input input-bordered w-full"
                        placeholder="Enter CNIC number" />
                    @error('cnic')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Address</label>
                    <input type="text" wire:model.defer="address" class="input input-bordered w-full"
                        placeholder="Enter address" />
                    @error('address')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Joining Date</label>
                    <input type="date" wire:model.defer="joiningDate" class="input input-bordered w-full"
                        placeholder="Select joining date" />
                    @error('joiningDate')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="label">Status</label>
                    <select wire:model.defer="status" class="select select-bordered w-full">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="on_leave">On Leave</option>
                    </select>
                </div>
            </div>
            <div class="modal-action flex gap-2">
                <button type="button" class="btn" wire:click="popUpHide">Cancel</button>
                <button type="submit" class="btn btn-primary">{{ $employeeId ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </dialog>

    <!-- Employees Table Section -->
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Branch</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Phone</th>
                    <th>CNIC</th>
                    <th>Address</th>
                    <th>Joining Date</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    <tr>
                        <td>{{ optional($employee->user)->name ?? '-' }}</td>
                        <td>{{ $employee->branch->name }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->cnic }}</td>
                        <td>{{ $employee->address }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d M, Y') }}</td>
                        <td>{{ $employee->status }}</td>
                        <td class="text-right space-x-2">
                            @can('Edit Employee')
                                <button class="btn btn-sm btn-outline" wire:click="edit({{ $employee->id }})">Edit</button>
                            @endcan
                            @can('Delete Employee')
                                <button class="btn btn-sm btn-error"
                                    wire:click="delete({{ $employee->id }})">Delete</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-gray-400 py-6">No employees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
