<?php

namespace App\Livewire\Pages;

use App\Models\Notice;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use App\Models\webdesign;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Manager Dashboard')]
class ManagerDashboard extends Component
{
  public $teamStats;
  public $recentNotices;
  public $department;
  public $employee;
  public $user;
  public $myProjects;
  public $myCampaigns;
  public $recentActivities;
  public $performanceStats;
  public $upcomingDeadlines;
  public $departmentStats;
  public $hasEmployeeRecord = false;
  public $latestNotices;
  public $departmentsStats = [];
  public $isManagerOrAdmin = false;

  public function mount()
  {
    $this->user = Auth::user();
    $this->isManagerOrAdmin = $this->user->hasAnyRole(['Manager', 'Admin']);

    // Properly load employee record with user relationship
    $this->employee = Employee::with('user')->where('user_id', $this->user->id)->first();
    $this->hasEmployeeRecord = $this->employee !== null;

    if ($this->hasEmployeeRecord) {
      $this->department = $this->employee->department ?? 'Unknown';
    }

    $this->recalculateAllMarketingPerformance();
    $this->loadDepartmentsStats();
    $this->loadRecentNotices();
    $this->loadEmployeeData();
    $this->loadPerformanceStats();
    $this->loadUpcomingDeadlines();
    $this->loadRecentActivities();
  }

  /**
   * Recalculate performance for all Marketing records to ensure dashboard accuracy.
   */
  public function recalculateAllMarketingPerformance()
  {
    foreach (Marketing::all() as $marketing) {
      $marketing->calculatePerformance();
    }
  }

  public function loadTeamStats()
  {
    if ($this->department) {
      $this->teamStats = Employee::where('department', $this->department)
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();
    } else {
      $this->teamStats = [];
    }
  }

  public function loadRecentNotices()
  {
    // $this->recentNotices = Notice::where('target_type', 'all')
    //   ->orWhereHas('users', function ($q) {
    //     $q->where('users.id', Auth::id());
    //   })
    //   ->latest()
    //   ->take(5)
    //   ->get();

    $userId = Auth::id();
    $this->latestNotices = Notice::with('creator')->where('target_type', 'all')
      ->orWhereHas('users', function ($q) use ($userId) {
        $q->where('users.id', $userId);
      })
      ->latest()
      ->take(2)
      ->get();
  }

  public function loadEmployeeData()
  {
    if ($this->isManagerOrAdmin) {
      $this->myProjects = webdesign::with(['employee.user'])->orderBy('created_at', 'desc')->take(5)->get();
      $this->myCampaigns = Marketing::with(['employee.user'])->orderBy('created_at', 'desc')->take(5)->get();

      // Fix the department stats query to include all necessary columns
      $this->departmentStats = Employee::with('user')
        ->select('department', 'status', 'user_id')
        ->groupBy('department', 'status', 'user_id')
        ->get()
        ->groupBy('department');
    } elseif ($this->employee) {
      $this->myProjects = webdesign::with(['employee.user'])->where('employee_id', $this->employee->id)
        ->orderBy('created_at', 'desc')->take(5)->get();
      $this->myCampaigns = Marketing::with(['employee.user'])->where('employee_id', $this->employee->id)
        ->orderBy('created_at', 'desc')->take(5)->get();

      // Fix the department stats query to include all necessary columns
      $this->departmentStats = Employee::with('user')
        ->where('department', $this->department)
        ->select('status', 'user_id')
        ->groupBy('status', 'user_id')
        ->get()
        ->pluck('status')
        ->countBy()
        ->toArray();
    }
  }

  public function loadPerformanceStats()
  {
    if ($this->isManagerOrAdmin) {
      $this->performanceStats = [
        'total_projects' => webdesign::count(),
        'completed_projects' => webdesign::where('status', 'completed')->count(),
        'total_campaigns' => Marketing::count(),
        'active_campaigns' => Marketing::where('status', 'active')->count(),
        'avg_performance' => webdesign::avg('performance') ?? 0,
        'avg_campaign_performance' => Marketing::avg('performance') ?? 0
      ];
    } elseif ($this->employee) {
      $this->performanceStats = [
        'total_projects' => webdesign::where('employee_id', $this->employee->id)->count(),
        'completed_projects' => webdesign::where('employee_id', $this->employee->id)->where('status', 'completed')->count(),
        'total_campaigns' => Marketing::where('employee_id', $this->employee->id)->count(),
        'active_campaigns' => Marketing::where('employee_id', $this->employee->id)
          // ->where('status', 'active')
          ->count(),
        'avg_performance' => webdesign::where('employee_id', $this->employee->id)->avg('performance') ?? 0,
        'avg_campaign_performance' => Marketing::where('employee_id', $this->employee->id)->avg('performance') ?? 0
      ];
    }
  }

  public function loadUpcomingDeadlines()
  {
    if ($this->employee) {
      $this->upcomingDeadlines = collect();
      if ($this->isManagerOrAdmin) {
        $projectDeadlines = webdesign::with(['employee.user'])->where('status', 'in_progress')
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
        // Add campaign deadlines for all employees if needed
        $this->upcomingDeadlines = $projectDeadlines;
      } else {
        $projectDeadlines = webdesign::with(['employee.user'])->where('employee_id', $this->employee->id)
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
        $this->upcomingDeadlines = $projectDeadlines;
      }
    }
  }

  public function loadRecentActivities()
  {
    if ($this->employee) {
      $this->recentActivities = collect();
      if ($this->isManagerOrAdmin) {
        $projectActivities = webdesign::with(['employee.user'])->orderBy('updated_at', 'desc')
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
        $campaignActivities = Marketing::with(['employee.user'])->orderBy('updated_at', 'desc')
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
      } else {
        if ($this->department === 'web design') {
          $this->recentActivities = webdesign::with(['employee.user'])->where('employee_id', $this->employee->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
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
          $this->recentActivities = Marketing::with(['employee.user'])->where('employee_id', $this->employee->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
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
      }
    }
  }

  public function loadDepartmentsStats()
  {
    if ($this->isManagerOrAdmin) {
      $departments = Employee::select('department')->distinct()->pluck('department');
      $this->departmentsStats = [];
      foreach ($departments as $dept) {
        $total = Employee::with('user')->where('department', $dept)->count();
        $active = Employee::with('user')->where('department', $dept)->where('status', 'active')->count();
        $projects = \App\Models\webdesign::with(['employee.user'])->whereHas('employee', function ($q) use ($dept) {
          $q->where('department', $dept);
        })->count();
        $campaigns = \App\Models\Marketing::with(['employee.user'])->whereHas('employee', function ($q) use ($dept) {
          $q->where('department', $dept);
        })->count();
        $this->departmentsStats[$dept] = [
          'total_employees' => $total,
          'active_employees' => $active,
          'projects' => $projects,
          'campaigns' => $campaigns,
        ];
      }
    } elseif ($this->employee) {
      // Only their own department
      $dept = $this->department;
      $total = Employee::with('user')->where('department', $dept)->count();
      $active = Employee::with('user')->where('department', $dept)->where('status', 'active')->count();
      $projects = \App\Models\webdesign::with(['employee.user'])->whereHas('employee', function ($q) use ($dept) {
        $q->where('department', $dept);
      })->count();
      $campaigns = \App\Models\Marketing::with(['employee.user'])->whereHas('employee', function ($q) use ($dept) {
        $q->where('department', $dept);
      })->count();
      $this->departmentsStats[$dept] = [
        'total_employees' => $total,
        'active_employees' => $active,
        'projects' => $projects,
        'campaigns' => $campaigns,
      ];
    }
  }

  public function render()
  {
    return view('livewire.pages.manager-dashboard');
  }
}