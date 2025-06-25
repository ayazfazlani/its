<div class="container" wire:ignore.self>
    <div>
        <!-- Header Section -->
        <div>
            <!-- Filter Button -->

            <!-- Add Button -->
            <button type="button" class="btn btn-outline-primary btn-md mt-5" wire:click="popUp">
                Add
            </button>
        </div>

        <!-- Modal Section -->
        <div wire:ignore.self class="modal fade" id="modeel">
            <div class="modal-dialog modal-dialog-centered">
                <form wire:submit="{{ $selectedStaff ? 'update' : 'store' }}" class="modal-content" wire:ignore.self>
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $selectedStaff ? 'Edit Doctor' : 'Add Doctor' }}</h5>
                        <button type="button" class="btn-close" wire:click="popUpHide"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model.live="name" class="form-control"
                                    placeholder="Enter doctor's name" />
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" wire:model.live="email" class="form-control"
                                    placeholder="Enter specialization" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <label class="form-label">Branch</label>
                                <select wire:model.live="branch_id" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}-{{ $branch->location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select wire:model.live="role" class="form-control">
                                    <option value="">Select Role</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="branch_admin">Branch Admin</option>
                                    <option value="doctor">Doctor</option>
                                    <option value="user">User</option>
                                </select>
                                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                            </div> --}}
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    wire:model.live="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-outline-secondary" wire:click="popUpHide">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">{{ $selectedStaff ? 'Update' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Doctors Table Section -->
        <div class="content-wrapper">
            <div class="mt-4">
                <div class="card">
                    <h5 class="card-header">Users List</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Branch</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffs as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->branch->name }}-{{ $user->branch->location }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="edit({{ $user->id }})">Edit</button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="delete({{ $user->id }})">Delete</button>
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

    @script
        <script>
            $wire.on('popUpShow', () => {
                $('#modeel').modal('show');
            });
            $wire.on('pop', () => {
                $('#modeel').modal('hide');
            });
        </script>
    @endscript
</div>
