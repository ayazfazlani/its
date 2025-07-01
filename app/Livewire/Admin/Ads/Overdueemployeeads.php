<?php

namespace App\Livewire\Admin\Ads;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;

class Overdueemployeeads extends Component
{

    
    public $employees;
    public function mount(): void
    {
        $employeeIds = Marketing::where('status','inActive')->pluck('employee_id')->unique();
        $employees = Employee::with('user')->whereIn('id',$employeeIds)->get();

        foreach($employees as $employee){
            $overdueAds = $employee->marketing()->where('status','inActive')->get();
            $employee->overdue_ads_count = $overdueAds->count();
            $employee->overdue_ads_avg_performance = $overdueAds->avg('performance') ?? 0;
        }
        $this->employees = $employees;
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.ads.overdueemployeeads');
    }
}
