<div class="min-h-screen bg-base-200">
    <!-- Header -->
    <div class="bg-base-100 shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-warning">🗑️ Scrap/Waste Record</h1>
                    <p class="text-base-content/70 mt-1">Record daily scrap and waste materials</p>
                </div>
                <a href="{{ route('warehouse.index') }}" class="btn btn-outline btn-warning">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Warehouse
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <!-- Success/Error Messages -->
        @if (session()->has('message'))
            <div class="alert alert-success mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-error mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Scrap/Waste Form -->
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h2 class="card-title text-warning mb-6">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            New Scrap/Waste Record
                        </h2>

                        <form wire:submit.prevent="save" class="space-y-6">
                            <!-- Raw Material Selection -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Raw Material *</span>
                                </label>
                                <select wire:model="raw_material_id"
                                    class="select select-bordered w-full @error('raw_material_id') select-error @enderror">
                                    <option value="">Select Raw Material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->id }}">{{ $material->name }}
                                            ({{ $material->code }})</option>
                                    @endforeach
                                </select>
                                @error('raw_material_id')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Quantity (kg) *</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" wire:model="quantity" step="0.001" min="0.001"
                                        class="input input-bordered w-full @error('quantity') input-error @enderror"
                                        placeholder="Enter waste quantity in kg">
                                    <span class="btn btn-square btn-outline">kg</span>
                                </div>
                                @error('quantity')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Reason -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Reason *</span>
                                </label>
                                <select wire:model="reason"
                                    class="select select-bordered w-full @error('reason') select-error @enderror">
                                    <option value="">Select Reason</option>
                                    <option value="Production Error">Production Error</option>
                                    <option value="Quality Issue">Quality Issue</option>
                                    <option value="Machine Malfunction">Machine Malfunction</option>
                                    <option value="Material Defect">Material Defect</option>
                                    <option value="Process Waste">Process Waste</option>
                                    <option value="Temperature Issue">Temperature Issue</option>
                                    <option value="Pressure Problem">Pressure Problem</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('reason')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Waste Date -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Waste Date *</span>
                                </label>
                                <input type="date" wire:model="waste_date"
                                    class="input input-bordered w-full @error('waste_date') input-error @enderror">
                                @error('waste_date')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Notes</span>
                                </label>
                                <textarea wire:model="notes" rows="3" class="textarea textarea-bordered @error('notes') textarea-error @enderror"
                                    placeholder="Additional details about the waste (optional)"></textarea>
                                @error('notes')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-control pt-4">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Record Scrap/Waste
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-lg">
                    <div class="card-body">
                        <h3 class="card-title text-info">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Waste Management Guidelines
                        </h3>
                        <div class="space-y-4">
                            <div class="alert alert-warning">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                                <div>
                                    <h4 class="font-bold">Daily Recording</h4>
                                    <p class="text-sm">Record all scrap and waste materials daily</p>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-bold">Waste Analysis</h4>
                                    <p class="text-sm">Track waste ratios vs acceptable standards</p>
                                </div>
                            </div>

                            <div class="alert alert-error">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-bold">Quality Control</h4>
                                    <p class="text-sm">Identify and address quality issues promptly</p>
                                </div>
                            </div>
                        </div>

                        <!-- Common Waste Reasons -->
                        <div class="divider"></div>
                        <h4 class="font-semibold mb-3">Common Waste Reasons</h4>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2 p-2 bg-base-200 rounded-lg">
                                <div class="badge badge-error badge-sm"></div>
                                <span class="text-sm">Production Error</span>
                            </div>
                            <div class="flex items-center space-x-2 p-2 bg-base-200 rounded-lg">
                                <div class="badge badge-error badge-sm"></div>
                                <span class="text-sm">Quality Issue</span>
                            </div>
                            <div class="flex items-center space-x-2 p-2 bg-base-200 rounded-lg">
                                <div class="badge badge-error badge-sm"></div>
                                <span class="text-sm">Machine Malfunction</span>
                            </div>
                            <div class="flex items-center space-x-2 p-2 bg-base-200 rounded-lg">
                                <div class="badge badge-error badge-sm"></div>
                                <span class="text-sm">Material Defect</span>
                            </div>
                            <div class="flex items-center space-x-2 p-2 bg-base-200 rounded-lg">
                                <div class="badge badge-error badge-sm"></div>
                                <span class="text-sm">Process Waste</span>
                            </div>
                        </div>

                        <!-- Waste Ratio Information -->
                        <div class="divider"></div>
                        <h4 class="font-semibold mb-3">Waste Ratio Standards</h4>
                        <div class="stats stats-vertical shadow">
                            <div class="stat">
                                <div class="stat-title">Acceptable Waste</div>
                                <div class="stat-value text-success">≤ 2%</div>
                                <div class="stat-desc">of total production</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Warning Level</div>
                                <div class="stat-value text-warning">2-5%</div>
                                <div class="stat-desc">requires investigation</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Critical Level</div>
                                <div class="stat-value text-error">> 5%</div>
                                <div class="stat-desc">immediate action needed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
