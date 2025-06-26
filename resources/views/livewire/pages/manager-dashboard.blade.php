<div class="flex flex-col gap-8 min-h-[40rem] w-full">
    <!-- Welcome Section -->
    <div class="card bg-primary text-primary-content shadow-xl">
        <div class="card-body flex flex-row justify-between items-center">
            <div>
                <h4 class="card-title mb-1 text-2xl">Welcome back, {{ $user->name }}!</h4>
                <p class="mb-0">{{ $employee?->position ?? 'Employee' }}</p>
            </div>
            <div class="avatar">
                <div class="w-16 rounded-full bg-white text-primary flex items-center justify-center">
                    <i class="bx bx-user-circle text-5xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notices Section -->
    <div class="card bg-info-content shadow-xl">
        <div class="card-body">
            <h5 class="card-title mb-4 text-info">Latest Notices</h5>
            @if ($latestNotices && count($latestNotices) > 0)
                <ul class="space-y-4">
                    @foreach ($latestNotices as $notice)
                        <li class="p-4 rounded-lg bg-base-100 shadow flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="badge {{ $notice->target_type === 'all' ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $notice->target_type === 'all' ? 'Team' : 'Individual' }}
                                </span>
                                <span class="font-bold text-lg">{{ $notice->title }}</span>
                            </div>
                            <div class="text-base-content/80">{{ $notice->content }}</div>
                            <div class="text-xs text-base-content/60 mt-1">
                                By: {{ $notice->creator->name ?? 'Unknown' }} |
                                {{ $notice->created_at->format('d M Y H:i') }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info mt-2">No notices for today.</div>
            @endif
        </div>
    </div>

    <!-- Performance Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card bg-base-100 shadow">
            <div class="card-body flex flex-row items-center justify-between">
                <div>
                    <h5 class="card-title mb-1">
                        @if($isManagerOrAdmin)
                            Total Projects
                        @else
                            My Projects
                        @endif
                    </h5>
                    <h4 class="text-2xl font-bold">{{ $performanceStats['total_projects'] ?? 0 }}</h4>
                    <small class="text-base-content/70">{{ $performanceStats['completed_projects'] ?? 0 }} completed</small>
                </div>
                <div class="avatar">
                    <div class="w-12 rounded-full bg-primary text-primary-content flex items-center justify-center">
                        <i class="bx bx-code-alt text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow">
            <div class="card-body flex flex-row items-center justify-between">
                <div>
                    <h5 class="card-title mb-1">
                        @if($isManagerOrAdmin)
                            Total Campaigns
                        @else
                            My Campaigns
                        @endif
                    </h5>
                    <h4 class="text-2xl font-bold">{{ $performanceStats['total_campaigns'] ?? 0 }}</h4>
                    <small class="text-base-content/70">{{ $performanceStats['active_campaigns'] ?? 0 }} active</small>
                </div>
                <div class="avatar">
                    <div class="w-12 rounded-full bg-info text-info-content flex items-center justify-center">
                        <i class="bx bx-bullseye text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow">
            <div class="card-body flex flex-row items-center justify-between">
                <div>
                    <h5 class="card-title mb-1">Project Performance</h5>
                    <h4 class="text-2xl font-bold">{{ number_format($performanceStats['avg_performance'] ?? 0, 1) }}%</h4>
                    <small class="text-base-content/70">Average score</small>
                </div>
                <div class="avatar">
                    <div class="w-12 rounded-full bg-success text-success-content flex items-center justify-center">
                        <i class="bx bx-trending-up text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow">
            <div class="card-body flex flex-row items-center justify-between">
                <div>
                    <h5 class="card-title mb-1">Campaign Performance</h5>
                    <h4 class="text-2xl font-bold">{{ number_format($performanceStats['avg_campaign_performance'] ?? 0, 1) }}%</h4>
                    <small class="text-base-content/70">Average score</small>
                </div>
                <div class="avatar">
                    <div class="w-12 rounded-full bg-warning text-warning-content flex items-center justify-center">
                        <i class="bx bx-target-lock text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Overview -->
    @if($isManagerOrAdmin)
        <div class="flex flex-col gap-6 min-h-[40rem] w-full">
            <h2 class="text-2xl font-bold mb-2">Department Overview</h2>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($departmentsStats as $dept => $stats)
                    <div class="card bg-base-100 shadow-xl border border-base-200">
                        <div class="card-body">
                            <h3 class="card-title capitalize">{{ $dept }}</h3>
                            <div class="flex flex-col gap-2 mt-2">
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-primary">Total Employees</span>
                                    <span class="font-bold">{{ $stats['total_employees'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-success">Active Employees</span>
                                    <span class="font-bold">{{ $stats['active_employees'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-info">Projects</span>
                                    <span class="font-bold">{{ $stats['projects'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-warning">Campaigns</span>
                                    <span class="font-bold">{{ $stats['campaigns'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="flex flex-col gap-6 min-h-[40rem] w-full">
            <h2 class="text-2xl font-bold mb-2">My Department Overview</h2>
            <div class="grid gap-6 md:grid-cols-1">
                @foreach($departmentsStats as $dept => $stats)
                    <div class="card bg-base-100 shadow-xl border border-base-200">
                        <div class="card-body">
                            <h3 class="card-title capitalize">{{ $dept }}</h3>
                            <div class="flex flex-col gap-2 mt-2">
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-primary">Total Employees</span>
                                    <span class="font-bold">{{ $stats['total_employees'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-success">Active Employees</span>
                                    <span class="font-bold">{{ $stats['active_employees'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-info">Projects</span>
                                    <span class="font-bold">{{ $stats['projects'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="badge badge-warning">Campaigns</span>
                                    <span class="font-bold">{{ $stats['campaigns'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>