<?php

namespace App\Livewire\Admin\Ads;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;

class Pausedads extends Component
{

    public $employees;
    public function mount(): void
    {
        $employeeIds = Marketing::where('status','pause')->pluck('employee_id')->unique();
        $employees = Employee::with('user')->whereIn('id',$employeeIds)->get();

        foreach($employees as $employee){
            $pausedAds = $employee->marketing()->where('status','pause')->get();
            $employee->paused_ads_count = $pausedAds->count();
            $employee->paused_ads_avg_performance = $pausedAds->avg('performance') ?? 0;
        }
        $this->employees = $employees;
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.ads.pausedads');
    }
}
