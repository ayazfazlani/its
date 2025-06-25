<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use App\Models\webdesign;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use App\Models\Notice;

class EpmloyeeDashboard extends Component
{
    public $employee;
    public $user;
    public $department;
    public $myProjects;
    public $myCampaigns;
    public $recentActivities;
    public $performanceStats;
    public $upcomingDeadlines;
    public $departmentStats;
    public $hasEmployeeRecord = false;
    public $latestNotices;

    public function mount()
    {
        $this->user = Auth::user();
        $this->employee = $this->user->employee;
        $this->hasEmployeeRecord = $this->employee !== null;

        if ($this->hasEmployeeRecord) {
            $this->department = $this->employee->department ?? 'Unknown';
            $this->loadEmployeeData();
            $this->loadPerformanceStats();
            $this->loadUpcomingDeadlines();
            $this->loadRecentActivities();
            $this->loadLatestNotices();
        }
    }

    public function loadEmployeeData()
    {
        if ($this->employee) {
            // Load employee's projects
            $this->myProjects = webdesign::where('employee_id', $this->employee->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Load employee's campaigns
            $this->myCampaigns = Marketing::where('employee_id', $this->employee->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Load department statistics
            $this->departmentStats = Employee::where('department', $this->department)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();
        }
    }

    public function loadPerformanceStats()
    {
        if ($this->employee) {
            $this->performanceStats = [
                'total_projects' => webdesign::where('employee_id', $this->employee->id)->count(),
                'completed_projects' => webdesign::where('employee_id', $this->employee->id)
                    ->where('status', 'completed')->count(),
                'total_campaigns' => Marketing::where('employee_id', $this->employee->id)->count(),
                'active_campaigns' => Marketing::where('employee_id', $this->employee->id)
                    ->where('status', 'active')->count(),
                'avg_performance' => webdesign::where('employee_id', $this->employee->id)
                    ->avg('performance') ?? 0,
                'avg_campaign_performance' => Marketing::where('employee_id', $this->employee->id)
                    ->avg('performance') ?? 0
            ];
        }
    }

    public function loadUpcomingDeadlines()
    {
        if ($this->employee) {
            $this->upcomingDeadlines = collect();

            //     // Get upcoming project deadlines
            $projectDeadlines = webdesign::where('employee_id', $this->employee->id)
                ->where('status', 'in_progress')
                ->where('end_date', '>=', now())
                ->where('end_date', '<=', now()->addDays(7))
                ->get()
                ->map(function ($project) {
                    return [
                        'type' => 'Project',
                        'name' => $project->project_name,
                        'deadline' => $project->end_date,
                        'status' => 'urgent'
                    ];
                });

            //     // Get upcoming campaign deadlines
            //     $campaignDeadlines = Marketing::where('employee_id', $this->employee->id)
            //         ->where('status', 'active')
            //         ->where('end_date', '>=', now())
            //         ->where('end_date', '<=', now()->addDays(7))
            //         ->get()
            //         ->map(function ($campaign) {
            //             return [
            //                 'type' => 'Campaign',
            //                 'name' => $campaign->name,
            //                 'deadline' => $campaign->end_date,
            //                 'status' => 'urgent'
            //             ];
            //         });

            //     $this->upcomingDeadlines = $projectDeadlines->merge($campaignDeadlines)
            //         ->sortBy('deadline')
            //         ->take(5);
        }
    }

    // public function loadRecentActivities()
    // {
    //     if ($this->employee) {
    //         $this->recentActivities = collect();

    //         // Get recent project activities
    //         $projectActivities ? webdesign::where('employee_id', $this->employee->id)
    //             ->orderBy('updated_at', 'desc')
    //             ->take(3)
    //             ->get()
    //             ->map(function ($project) {
    //                 return [
    //                     'type' => 'Project',
    //                     'action' => 'Updated',
    //                     'name' => $project->project_name,
    //                     'time' => $project->updated_at->diffForHumans(),
    //                     'status' => $project->status
    //                 ];
    //             }) : '';

    //         // Get recent campaign activities
    //         $campaignActivities ? Marketing::where('employee_id', $this->employee->id)
    //             ->orderBy('updated_at', 'desc')
    //             ->take(3)
    //             ->get()
    //             ->map(function ($campaign) {
    //                 return [
    //                     'type' => 'Campaign',
    //                     'action' => 'Updated',
    //                     'name' => $campaign->name,
    //                     'time' => $campaign->updated_at->diffForHumans(),
    //                     'status' => $campaign->status
    //                 ];
    //             }) : '';

    //         $this->recentActivities = $projectActivities->merge($campaignActivities)
    //             ->sortByDesc('time')
    //             ->take(5);
    //     }
    // }

    public function loadRecentActivities()
    {
        if ($this->employee) {
            $this->recentActivities = collect();

            // Check employee department and load appropriate activities
            if ($this->department === 'web design') {
                $this->recentActivities = webdesign::where('employee_id', $this->employee->id)
                    ->orderBy('updated_at', 'desc')
                    ->take(5) // Get 5 directly since we're only showing one type
                    ->get()
                    ->map(function ($project) {
                        return [
                            'type' => 'Project',
                            'action' => 'Updated',
                            'name' => $project->project_name,
                            'time' => $project->updated_at->diffForHumans(),
                            'status' => $project->status
                        ];
                    });
            } elseif ($this->department === 'digital marketing') {
                $this->recentActivities = Marketing::where('employee_id', $this->employee->id)
                    ->orderBy('updated_at', 'desc')
                    ->take(5) // Get 5 directly since we're only showing one type
                    ->get()
                    ->map(function ($campaign) {
                        return [
                            'type' => 'Campaign',
                            'action' => 'Updated',
                            'name' => $campaign->name,
                            'time' => $campaign->updated_at->diffForHumans(),
                            'status' => $campaign->status
                        ];
                    });
            }

            // For admin or other departments (if needed)
            if ($this->user->hasanyRole('Manager|Admin')) {
                $projectActivities = webdesign::orderBy('updated_at', 'desc')
                    ->take(3)
                    ->get()
                    ->map(function ($project) {
                        return [
                            'type' => 'Project',
                            'action' => 'Updated',
                            'name' => $project->project_name,
                            'time' => $project->updated_at->diffForHumans(),
                            'status' => $project->status
                        ];
                    });

                $campaignActivities = Marketing::orderBy('updated_at', 'desc')
                    ->take(3)
                    ->get()
                    ->map(function ($campaign) {
                        return [
                            'type' => 'Campaign',
                            'action' => 'Updated',
                            'name' => $campaign->name,
                            'time' => $campaign->updated_at->diffForHumans(),
                            'status' => $campaign->status
                        ];
                    });

                $this->recentActivities = $projectActivities->concat($campaignActivities)
                    ->sortByDesc('time')
                    ->take(5);
            }
        }
    }

    public function loadLatestNotices()
    {
        $userId = $this->user->id;
        $this->latestNotices = Notice::with('creator')->where(function ($query) use ($userId) {
            $query->where('target_type', 'all')
                ->orWhereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
        })
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->take(5)
            ->get();
    }

    #[Title('Employee Dashboard')]
    public function render()
    {
        return view('livewire.pages.epmloyee-dashboard', [
            'latestNotices' => $this->latestNotices,
        ]);
    }
}