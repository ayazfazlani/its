<?php

namespace App\Livewire\Layouts;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use App\Models\webdesign;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Notice;
use Illuminate\Contracts\View\View;

#[Title('Layouts Dashboard')]

class Index extends Component
{
  public $totalEmployees;
  public $activeEmployees;
  public $totalProjects;
  public $completedProjects;
  public $inReviewProjects;
  public $activeProjects;
  public $delayedProjects;
  public $totalCampaigns;
  public $activeCampaigns;
  public $pausedCampaigns;
  public $cancelledCampaigns;
  public $departmentStats; // Only for department counts, not Employee models
  public $clientLeft;
  public $employees; // Use this for any Blade code that needs $employee->user

  public $title;
  // Chart Data
  public $monthlyProjects = [];
  public $monthlyCampaigns = [];
  public $employeeGrowth = [];
  public $performanceByDepartment = [];

  public $latestNotices;

  public function mount()
  {
    $this->loadDashboardData();
    // $this->loadChartData();
  }

  public function loadDashboardData()
  {
    $this->totalEmployees = Employee::count();
    $this->activeEmployees = Employee::where('status', 'active')->count();
    $this->totalProjects = webdesign::count();
    $this->completedProjects = webdesign::where('status', 'delivered')->count();
    $this->activeProjects =  webdesign::where('status', 'in progress')->count();
    $this->inReviewProjects = webdesign::where('status', 'in review')->count();
    $this->delayedProjects = webdesign::where('status', 'delayed')->count();
    $this->totalCampaigns = Marketing::count();
    $this->activeCampaigns = Marketing::where('status', 'active')->count();
    $this->pausedCampaigns = Marketing::where('status', 'pause')->count();
    $this->cancelledCampaigns = Marketing::where('status', 'inActive')->count();
    $this->clientLeft = Marketing::where('status', 'clientLeft')->count();

    // Department Statistics (array: department => count)
    $this->departmentStats = Employee::selectRaw('department, COUNT(*) as count')
      ->groupBy('department')
      ->get()
      ->pluck('count', 'department')
      ->toArray();

    // Eager load employees with user for any Blade usage
    $this->employees = Employee::with('user')->get();

    // Fetch latest 5 notices for the user
    $userId = Auth::id();
    $this->latestNotices = Notice::with('creator')->where('target_type', 'all')
      ->orWhereHas('users', function ($q) use ($userId) {
        $q->where('users.id', $userId);
      })
      ->latest()
      ->take(2)
      ->get();
  }

  public function loadChartData()
  {
    // Monthly Projects Data
    for ($i = 0; $i < 12; $i++) {
      $month = now()->subMonths($i);

      // Get projects created in the month
      $projects = webdesign::whereMonth('created_at', $month->month)
        ->whereYear('created_at', $month->year)
        ->count();

      // Get campaigns created in the month
      $campaigns = Marketing::whereMonth('created_at', $month->month)
        ->whereYear('created_at', $month->year)
        ->count();

      $this->monthlyProjects[] = [
        'month' => $month->format('M'),
        'count' => $projects
      ];

      $this->monthlyCampaigns[] = [
        'month' => $month->format('M'),
        'count' => $campaigns
      ];
    }

    // Employee Growth Data
    for ($i = 0; $i < 12; $i++) {
      $month = now()->subMonths($i);
      $employees = Employee::whereMonth('created_at', '<=', $month->month)
        ->whereYear('created_at', '<=', $month->year)
        ->count();

      $this->employeeGrowth[] = [
        'month' => $month->format('M'),
        'count' => $employees
      ];
    }

    // Performance by Department
    $this->performanceByDepartment = Employee::with('user')
      ->selectRaw('department, AVG(webdesigns.performance) as avg_performance')
      ->leftJoin('webdesigns', 'employees.id', '=', 'webdesigns.employee_id')
      ->groupBy('department')
      ->get()
      ->map(function ($item) {
        return [
          'department' => $item->department,
          'performance' => round($item->avg_performance ?? 0, 2)
        ];
      });
  }

  #[Title('Dashboard')]
  public function render()
  {
    return view('livewire.layouts.index', [
      'latestNotices' => $this->latestNotices,
    ]);
  }
}
