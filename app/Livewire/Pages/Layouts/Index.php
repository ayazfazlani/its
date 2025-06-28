<?php

namespace App\Livewire\Pages\Layouts;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use App\Models\webdesign;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

#[Title('Layouts Dashboard')]
#[Layout('components.layouts.app')]
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
    public $departmentStats;
    public $clientLeft;

    public $title;
    // Chart Data
    public $monthlyProjects = [];
    public $monthlyCampaigns = [];
    public $employeeGrowth = [];
    public $performanceByDepartment = [];

    public function mount()
    {

        $this->loadDashboardData();
        $this->loadChartData();
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

        // Department Statistics
        $this->departmentStats = Employee::selectRaw('department, COUNT(*) as count')
            ->groupBy('department')
            ->get()
            ->pluck('count', 'department')
            ->toArray();
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
        // $this->performanceByDepartment = Employee::with('user')
        // ->selectRaw('department, employees.id, employees.user_id, AVG(webdesigns.performance) as avg_performance')
        // ->leftJoin('webdesigns', 'employees.id', '=', 'webdesigns.employee_id')
        // ->groupBy('department', 'employees.id', 'employees.user_id')
        // ->get()
        // ->map(function ($item) {
        //     return [
        //         'department' => $item->department,
        //         'performance' => round($item->avg_performance ?? 0, 2)
        //     ];
        // });
    }


    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.layouts.indexs');
    }
}
