<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Ad Details</h2>
        <button class="btn btn-primary" wire:click="popUp">Add Detail</button>
    </div>

    {{-- <!-- Modal -->
    <dialog id="ad-detail-modal" class="modal" @if($showModal) open @endif>
        <form method="dialog" class="modal-box" wire:submit.prevent="save">
            <h3 class="font-bold text-lg mb-4">{{ $editingId ? 'Edit' : 'Add' }} Ad Detail</h3>
            <div class="form-control mb-2">
                <label class="label">Marketing</label>
                <select wire:model.defer="marketingId" class="select select-bordered">
                    <option value="">Select Marketing</option>
                    @foreach($marketings as $marketing)
                        <option value="{{ $marketing->id }}">{{ $marketing->name }}</option>
                    @endforeach
                </select>
                @error('marketingId') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="form-control mb-2">
                <label class="label">Clicks</label>
                <input type="number" wire:model.defer="clicks" class="input input-bordered" min="0" />
                @error('clicks') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="form-control mb-2">
                <label class="label">Calls</label>
                <input type="number" wire:model.defer="calls" class="input input-bordered" min="0" />
                @error('calls') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="form-control mb-2">
                <label class="label">Budget Spent</label>
                <input type="number" wire:model.defer="budgetSpent" class="input input-bordered" min="0" step="0.01" />
                @error('budgetSpent') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="form-control mb-2">
                <label class="label">Note</label>
                <textarea wire:model.defer="note" class="textarea textarea-bordered"></textarea>
                @error('note') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="modal-action">
                <button type="button" class="btn" wire:click="popUpHide">Cancel</button>
                <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Save' }}</button>
            </div>
        </form>
    </dialog> --}}
    <!-- Modal -->
<dialog id="ad-detail-modal" class="modal" @if ($showModal || $editingId !== null) open @endif>
    <form method="dialog" class="modal-box w-full max-w-2xl" wire:submit.prevent="save">
        <h3 class="font-bold text-lg mb-4">{{ $editingId ? 'Edit' : 'Add' }} Ad Detail</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="label">Marketing</label>
                <select wire:model.defer="marketingId" class="select select-bordered w-full">
                    <option value="">Select Marketing</option>
                    @foreach($marketings as $marketing)
                        <option value="{{ $marketing->id }}">{{ $marketing->name }}</option>
                    @endforeach
                </select>
                @error('marketingId') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="label">Clicks</label>
                <input type="number" wire:model.defer="clicks" class="input input-bordered w-full" min="0" />
                @error('clicks') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="label">Calls</label>
                <input type="number" wire:model.defer="calls" class="input input-bordered w-full" min="0" />
                @error('calls') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="label">Budget Spent</label>
                <input type="number" wire:model.defer="budgetSpent" class="input input-bordered w-full" min="0" step="0.01" />
                @error('budgetSpent') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="label">Note</label>
                <textarea wire:model.defer="note" class="textarea textarea-bordered w-full"></textarea>
                @error('note') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="modal-action flex gap-2">
            <button type="button" class="btn" wire:click="popUpHide">Cancel</button>
            <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Save' }}</button>
        </div>
    </form>
</dialog>

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Marketing</th>
                    <th>Clicks</th>
                    <th>Calls</th>
                    <th>Budget Spent</th>
                    <th>Performance (%)</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($adDetails as $detail)
                    <tr>
                        <td>{{ optional($detail->marketing)->name ?? '-' }}</td>
                        <td>{{ $detail->clicks }}</td>
                        <td>{{ $detail->calls }}</td>
                        <td>{{ $detail->budget_spent }}</td>
                        <td>{{ $detail->performance }}</td>
                        <td>{{ $detail->note }}</td>
                        <td>
                            <button class="btn btn-xs btn-outline" wire:click="edit({{ $detail->id }})">Edit</button>
                            <button class="btn btn-xs btn-error" wire:click="delete({{ $detail->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>