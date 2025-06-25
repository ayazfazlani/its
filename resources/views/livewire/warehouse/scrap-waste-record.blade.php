<div class="p-6">
    <div class="p-6">
        @if ($showForm)
            <div class="bg-base-200 p-6 rounded-lg mb-6 shadow">
                <h2 class="text-lg font-semibold mb-4">{{ $editingId ? 'Edit' : 'Create' }} Scrap/Waste</h2>

                <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Date</label>
                        <input type="date" class="input input-bordered w-full" wire:model.defer="date">
                        @error('date')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Type</label>
                        <select class="select select-bordered w-full" wire:model="type">
                            <option value="">Select</option>
                            <option value="scrap">Scrap</option>
                            <option value="waste">Waste</option>
                        </select>
                        @error('type')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($type === 'scrap')
                        <div>
                            <label class="label">Raw Material</label>
                            <select class="select select-bordered w-full" wire:model="material_id">
                                <option value="">Select Material</option>
                                @foreach ($rawMaterials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @elseif ($type === 'waste')
                        <div>
                            <label class="label">Product</label>
                            <select class="select select-bordered w-full" wire:model="product_id">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label class="label">Quantity</label>
                        <input type="number" class="input input-bordered w-full" wire:model.defer="quantity">
                        @error('quantity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Unit</label>
                        <input type="text" class="input input-bordered w-full" wire:model.defer="unit">
                        @error('unit')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="label">Reason</label>
                        <textarea class="textarea textarea-bordered w-full" wire:model.defer="reason"></textarea>
                        @error('reason')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Disposal Method</label>
                        <input type="text" class="input input-bordered w-full" wire:model.defer="disposal_method">
                        @error('disposal_method')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="label">Cost (optional)</label>
                        <input type="number" class="input input-bordered w-full" wire:model.defer="cost">
                        @error('cost')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- 
                    <div class="md:col-span-2">
                        <label class="label">Notes</label>
                        <textarea class="textarea textarea-bordered w-full" wire:model.defer="notes"></textarea>
                        @error('notes')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <div class="md:col-span-2 flex gap-3 mt-2">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-outline" wire:click="cancel" type="button">Cancel</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="mb-4">
            <button class="btn btn-accent" wire:click="create">+ Add Scrap/Waste</button>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Material/Product</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        {{-- <th>Status</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($scrapWasteRecords as $record)
                        <tr>
                            <td>{{ $record->date }}</td>
                            <td class="capitalize">{{ $record->type }}</td>
                            <td>
                                {{ $record->type === 'scrap' ? $record->rawMaterial->name ?? '-' : $record->product->name ?? '-' }}
                            </td>
                            <td>{{ $record->quantity }}</td>
                            <td>{{ $record->unit }}</td>
                            <td>
                                <span
                                    class="badge {{ $record->status === 'approved' ? 'badge-success' : ($record->status === 'rejected' ? 'badge-error' : 'badge-warning') }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="flex gap-2">
                                <button class="btn btn-xs btn-outline"
                                    wire:click="edit({{ $record->id }})">Edit</button>
                                <button class="btn btn-xs btn-outline btn-error"
                                    wire:click="delete({{ $record->id }})">Delete</button>
                                {{-- @if ($record->status === 'pending')
                                    <button class="btn btn-xs btn-outline btn-success"
                                        wire:click="approve({{ $record->id }})">Approve</button>
                                    <button class="btn btn-xs btn-outline btn-warning"
                                        wire:click="reject({{ $record->id }})">Reject</button>
                                @endif --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $scrapWasteRecords->links() }}
            </div>
        </div>
    </div>

</div>
