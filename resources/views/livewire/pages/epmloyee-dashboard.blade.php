<div>
    @php
        $user = Auth::user();
        $department = $user?->employee?->department;
        $isWebDesign = $department === 'web design';
        $isAdsexpert = $department === 'digital marketing';
        $isAdmin = $user->hasRole('Admin');

    @endphp
    @if (!$hasEmployeeRecord)
        <!-- Welcome Section for New Users -->
        <div class="row m-3">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Welcome to ERMS, {{ $user->name }}!</h4>
                                <p class="mb-0">Employee Resource Management System</p>
                            </div>
                            <div class="avatar">
                                <div class="avatar-content bg-white text-primary">
                                    <i class="bx bx-user-circle bx-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Setup Required Message -->
        <div class="row m-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bx bx-user-plus bx-lg text-primary mb-3"></i>
                            <h4>Employee Profile Setup Required</h4>
                            <p class="text-muted">Your employee profile needs to be set up by an administrator.</p>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">What happens next?</h6>
                                    <ul class="mb-0 text-start">
                                        <li>An administrator will create your employee profile</li>
                                        <li>You'll be assigned to a department and position</li>
                                        <li>You'll be able to access department-specific features</li>
                                        <li>Your projects and campaigns will be tracked here</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted">Please contact your administrator to complete the setup process.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Welcome Section -->
        <div class="row m-3">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1">Welcome back, {{ $user->name }}!</h4>
                                <p class="mb-0">
                                    {{-- {{ ucwords($department) }}  --}}
                                    {{ $employee->position ?? 'Employee' }}</p>

                            </div>
                            <div class="avatar">
                                <div class="avatar-content bg-white text-primary">
                                    <i class="bx bx-user-circle bx-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row m-6 card p-3">
            <h5 class="mt-3">Notice Board</h5>
            <p>Notice board for whole team members and for specific employee</p>

            @if ($latestNotices && count($latestNotices) > 0)
                @foreach ($latestNotices as $notice)
                    <div
                        class="row m-1 {{ $notice->target_type === 'all' ? 'bg-info text-white' : 'border' }} rounded p-3">
                        <div class="col">
                            <strong>{{ $notice->title }}</strong>
                            <p class="mb-0">{{ $notice->content }}</p>
                            <small>
                                By: {{ $notice->creator->name ?? 'Unknown' }} |
                                {{ $notice->created_at->format('d M Y H:i') }}
                                @if ($notice->target_type === 'all')
                                    <span class="badge bg-primary">Team</span>
                                @else
                                    <span class="badge bg-secondary">Individual</span>
                                @endif
                            </small>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">No notices for today.</div>
            @endif
        </div>

        <!-- Performance Statistics -->
        <div class="row m-3">
            <!-- Total Projects - Visible to all departments -->
            @if (!$isAdsexpert || $isAdmin)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">
                                    <h5 class="mb-1">My Projects</h5>
                                    <h4 class="mb-0">{{ $performanceStats['total_projects'] ?? 0 }}</h4>
                                    <small class="text-muted">{{ $performanceStats['completed_projects'] ?? 0 }}
                                        completed</small>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-content bg-label-primary">
                                        <i class="bx bx-code-alt bx-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Campaign Stats - Only for Web Design and Admin -->
            @if (!$isWebDesign || $isAdmin)
                <!-- Total Campaigns -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">
                                    <h5 class="mb-1">My Campaigns</h5>
                                    <h4 class="mb-0">{{ $performanceStats['total_campaigns'] ?? 0 }}</h4>
                                    <small class="text-muted">{{ $performanceStats['active_campaigns'] ?? 0 }}
                                        active</small>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-content bg-label-info">
                                        <i class="bx bx-bullseye bx-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$isAdsexpert || $isAdmin)
                <!-- Average Performance - Visible to all departments -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">
                                    <h5 class="mb-1">Project Performance</h5>
                                    <h4 class="mb-0">
                                        {{ number_format($performanceStats['avg_performance'] ?? 0, 1) }}%
                                    </h4>
                                    <small class="text-muted">Average score</small>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-content bg-label-success">
                                        <i class="bx bx-trending-up bx-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Campaign Performance - Only for Web Design and Admin -->
            @if (!$isWebDesign || $isAdmin)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">
                                    <h5 class="mb-1">Campaign Performance</h5>
                                    <h4 class="mb-0">
                                        {{ number_format($performanceStats['avg_campaign_performance'] ?? 0, 1) }}%
                                    </h4>
                                    <small class="text-muted">Average score</small>
                                </div>
                                <div class="avatar">
                                    <div class="avatar-content bg-label-warning">
                                        <i class="bx bx-target-lock bx-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Row -->
        <div class="row m-3">
            @if (!$isAdsexpert || $isAdmin)
                <!-- My Projects - Visible to all departments -->
                <div class="col-lg-{{ $isWebDesign || $isAdmin ? '6' : '12' }} mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">My Recent Projects</h5>
                            <a href="{{ route('web.active') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            @if ($myProjects && $myProjects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($myProjects as $project)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar me-3">
                                                                <div class="avatar-content bg-label-primary">
                                                                    <i class="bx bx-code-alt bx-sm"></i>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $project->project_name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $project->category }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span
                                                            class="badge bg-label-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                            {{ ucwords(str_replace('_', ' ', $project->status)) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-code-alt bx-lg text-muted mb-2"></i>
                                    <p class="text-muted">No projects found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <!-- My Campaigns - Only for Web Design and Admin -->
            @if (!$isWebDesign || $isAdmin)
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">My Recent Campaigns</h5>
                            <a href="{{ route('ads') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            @if ($myCampaigns && $myCampaigns->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($myCampaigns as $campaign)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar me-3">
                                                                <div class="avatar-content bg-label-info">
                                                                    <i class="bx bx-bullseye bx-sm"></i>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $campaign->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $campaign->performance }}%
                                                                    performance</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span
                                                            class="badge bg-label-{{ $campaign->status === 'active' ? 'success' : ($campaign->status === 'pause' ? 'warning' : 'danger') }}">
                                                            {{ ucwords($campaign->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-bullseye bx-lg text-muted mb-2"></i>
                                    <p class="text-muted">No campaigns found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (!$isAdsexpert || $isAdmin)
                <!-- Upcoming Deadlines and Recent Activities -->

                <!-- Upcoming Deadlines - Visible to all departments -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Upcoming Deadlines</h5>
                        </div>
                        <div class="card-body">
                            @if ($upcomingDeadlines && $upcomingDeadlines->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach ($upcomingDeadlines as $deadline)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $deadline['name'] }}</h6>
                                                    <small class="text-muted">{{ $deadline['type'] }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <span
                                                        class="badge bg-label-danger">{{ \Carbon\Carbon::parse($deadline['deadline'])->format('M d') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-calendar-check bx-lg text-muted mb-2"></i>
                                    <p class="text-muted">No upcoming deadlines</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <!-- Recent Activities - Visible to all departments -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        @if ($recentActivities && $recentActivities->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($recentActivities as $activity)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div class="avatar-content bg-label-primary">
                                                    <i
                                                        class="bx bx-{{ $activity['type'] === 'Project' ? 'code-alt' : 'bullseye' }} bx-sm"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $activity['name'] }}</h6>
                                                <small class="text-muted">{{ $activity['action'] }}
                                                    {{ $activity['time'] }}</small>
                                            </div>
                                            <span
                                                class="badge bg-label-{{ $activity['status'] === 'completed' ? 'success' : ($activity['status'] === 'active' ? 'primary' : 'warning') }}">
                                                {{ ucwords($activity['status']) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-activity bx-lg text-muted mb-2"></i>
                                <p class="text-muted">No recent activities</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Statistics - Visible to all departments -->
        @if ($departmentStats && count($departmentStats) > 0)
            <div class="row m-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ ucwords($department) }} Department Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($departmentStats as $status => $count)
                                    <div class="col-md-4 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div
                                                    class="avatar-content bg-label-{{ $status === 'active' ? 'success' : ($status === 'inactive' ? 'danger' : 'warning') }}">
                                                    <i class="bx bx-user bx-sm"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ ucwords($status) }} Employees</h6>
                                                <small class="text-muted">{{ $count }} in
                                                    {{ $department }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
