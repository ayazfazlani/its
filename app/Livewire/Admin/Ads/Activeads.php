<?php

namespace App\Livewire\Admin\Ads;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;

class Activeads extends Component
{
    public $employees = [];

    public function mount(): void
    {
        // Get all employees who have at least one active ad
        $employeeIds = Marketing::where('status', 'active')->pluck('employee_id')->unique();
        $employees = Employee::with('user')->whereIn('id', $employeeIds)->get();

        // For each employee, count their active ads
        foreach ($employees as $employee) {
            $activeAds = $employee->marketing()->where('status', 'active')->get();
            $employee->active_ads_count = $activeAds->count();
            // Calculate average performance (or use sum if you prefer)
            $employee->active_ads_performance = $activeAds->avg('performance') ?? 0;
        }

        $this->employees = $employees;
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.ads.activeads', [
            'employees' => $this->employees,
        ]);
    }
}
