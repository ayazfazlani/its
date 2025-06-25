<div class="flex flex-col gap-8 p-4 w-full">
    <!-- Welcome Section -->
    <div class="card bg-primary text-primary-content shadow-xl">
        <div class="card-body flex flex-row justify-between items-center">
            <div>
                <h4 class="card-title mb-1">Welcome to ERMS Dashboard</h4>
                <p class="mb-0">Employee Resource Management System</p>
            </div>
            <div class="avatar">
                <div class="w-16 rounded-full bg-white text-primary flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Notices Section -->
    <div class="card bg-info-content shadow-xl mt-6">
        <div class="card-body">
            <h5 class="card-title mb-4 text-info">Latest Notices</h5>
            @if ($latestNotices && count($latestNotices) > 0)
                <ul class="space-y-4">
                    @foreach ($latestNotices as $notice)
                        <li class="p-4 rounded-lg bg-base-100 shadow flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="badge {{ $notice->target_type === 'all' ? 'badge-primary' : 'badge-secondary' }}">
                                    {{ $notice->target_type === 'all' ? 'All' : 'Specific' }}
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
                <div class="alert alert-info mt-2">No notices available.</div>
            @endif
        </div>
    </div>
    <!-- Employee Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body flex flex-row justify-between items-center">
                <div>
                    <h5 class="card-title mb-1">Total Employees</h5>
                    <h2 class="text-3xl font-bold">{{ number_format($totalEmployees) }}</h2>
                </div>
                <div class="avatar placeholder">
                    <div class="bg-primary text-white rounded-full w-14 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 7v-6m0 0l-9-5m9 5l9-5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body flex flex-row justify-between items-center">
                <div>
                    <h5 class="card-title mb-1">Active Employees</h5>
                    <h2 class="text-3xl font-bold">{{ number_format($activeEmployees) }}</h2>
                </div>
                <div class="avatar placeholder">
                    <div class="bg-success text-white rounded-full w-14 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Web Development Projects Stats -->
    <div class="card bg-base-100 shadow-xl mt-6">
        <div class="card-body">
            <h5 class="card-title mb-4">Web Development Projects Stats</h5>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Total Projects</div>
                    <div class="stat-value">{{ number_format($totalProjects) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Active</div>
                    <div class="stat-value">{{ number_format($activeProjects) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">In Review</div>
                    <div class="stat-value">{{ number_format($inReviewProjects) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Completed</div>
                    <div class="stat-value">{{ number_format($completedProjects) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Delayed</div>
                    <div class="stat-value">{{ number_format($delayedProjects) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Digital Marketing Google Ads Stats -->
    <div class="card bg-base-100 shadow-xl mt-6">
        <div class="card-body">
            <h5 class="card-title mb-4">Digital Marketing Google Ads Stats</h5>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Total Ads</div>
                    <div class="stat-value">{{ number_format($totalCampaigns) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Active</div>
                    <div class="stat-value">{{ number_format($activeCampaigns) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Paused</div>
                    <div class="stat-value">{{ number_format($pausedCampaigns) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Cancelled</div>
                    <div class="stat-value">{{ number_format($cancelledCampaigns) }}</div>
                </div>
                <div class="stat bg-base-200 rounded-box">
                    <div class="stat-title">Client Left</div>
                    <div class="stat-value">{{ number_format($clientLeft) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Stats -->
    <div class="card bg-base-100 shadow-xl mt-6">
        <div class="card-body">
            <h5 class="card-title mb-4">Department Stats</h5>
            <div class="flex flex-wrap gap-4">
                @foreach ($departmentStats as $department => $count)
                    <div class="badge badge-lg badge-info">
                        {{ $department }}: {{ $count }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>


</div>
