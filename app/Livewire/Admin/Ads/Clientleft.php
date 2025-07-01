<?php

namespace App\Livewire\Admin\Ads;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;

class Clientleft extends Component
{
    public $employees = [];
    public function mount(): void
    {
        $employeeIds = Marketing::where('status','clientLeft')->pluck('employee_id')->unique();
        $employees = Employee::with('user')->whereIn('id',$employeeIds)->get();

        foreach($employees as $employee){
            $clientLeftAds = $employee->marketing()->where('status','clientLeft')->get();
            $employee->client_left_count = $clientLeftAds->count();
            $employee->client_left_avg_performance = $clientLeftAds->avg('performance') ?? 0;
        }
        $this->employees = $employees;
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.ads.clientleft');
    }
}
