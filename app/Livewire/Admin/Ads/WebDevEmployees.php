<?php

namespace App\Livewire\Admin\Ads;

use Livewire\Component;
use App\Models\Employee;
use App\Models\webdesign;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;

class WebDevEmployees extends Component
{
    public $employees = [];

    public function mount(): void
    {
        // Get all employees who have at least one web project
        $employeeIds = webdesign::pluck('employee_id')->unique();
        $employees = Employee::with('user')->whereIn('id', $employeeIds)->get();

        // For each employee, count their web projects
        foreach ($employees as $employee) {
            $webProjects = $employee->webdesign()->get();
            $employee->web_projects_count = $webProjects->count();
            $employee->web_projects_performance = $webProjects->avg('performance') ?? 0;
        }

        $this->employees = $employees;
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.ads.webdev-employees', [
            'employees' => $this->employees,
        ]);
    }
} 