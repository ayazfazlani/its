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

  public function mount()
  {
    $user = Auth::user();
    $this->department = $user->employee->department ?? null;
    $this->loadDepartmentsStats();
    $this->loadRecentNotices();

    $this->user = Auth::user();
    $this->employee = $this->user->employee ?? null;
    $this->hasEmployeeRecord = $this->employee !== null;

    if ($this->hasEmployeeRecord) {
      $this->department = $this->employee->department ?? 'Unknown';
      $this->loadEmployeeData();
      $this->loadPerformanceStats();
      $this->loadUpcomingDeadlines();
      $this->loadRecentActivities();
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
    $this->recentNotices = Notice::where('target_type', 'all')
      ->orWhereHas('users', function ($q) {
        $q->where('users.id', Auth::id());
      })
      ->latest()
      ->take(5)
      ->get();
  }

  public function loadEmployeeData()
  {
    if ($this->employee) {
      // Load employee's projects
      $this->myProjects = webdesign::orderBy('created_at', 'desc')
        ->take(5)
        ->get();

      // Load employee's campaigns
      $this->myCampaigns = Marketing::orderBy('created_at', 'desc')
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
        'total_projects' => webdesign::count(),
        'completed_projects' => webdesign::where('status', 'completed')->count(),
        'total_campaigns' => Marketing::count(),
        'active_campaigns' => Marketing::where('status', 'active')->count(),
        'avg_performance' => webdesign::avg('performance') ?? 0,
        'avg_campaign_performance' => Marketing::avg('performance') ?? 0
      ];
    }
  }

  public function loadUpcomingDeadlines()
  {
    if ($this->employee) {
      $this->upcomingDeadlines = collect();

      //     // Get upcoming project deadlines
      $projectDeadlines = webdesign::where('status', 'in_progress')
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
        $this->recentActivities = webdesign::orderBy('updated_at', 'desc')
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
        $this->recentActivities = Marketing::orderBy('updated_at', 'desc')
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

  public function loadDepartmentsStats()
  {
    $departments = Employee::select('department')->distinct()->pluck('department');
    $this->departmentsStats = [];
    foreach ($departments as $dept) {
      $total = Employee::where('department', $dept)->count();
      $active = Employee::where('department', $dept)->where('status', 'active')->count();
      $projects = \App\Models\webdesign::whereHas('employee', function($q) use ($dept) {
        $q->where('department', $dept);
      })->count();
      $campaigns = \App\Models\Marketing::whereHas('employee', function($q) use ($dept) {
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