<?php

namespace App\Livewire\Admin\Ads;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;

class ViewEmployeeAds extends Component
{
    public $employee;
    public $activeAds = [];

    public function mount($id): void
    {
        $this->employee = Employee::with('user')->findOrFail($id);
        $this->activeAds = $this->employee->marketing()->where('status', 'active')->get();
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.ads.view-employee-ads', [
            'employee' => $this->employee,
            'activeAds' => $this->activeAds,
        ]);
    }
}
