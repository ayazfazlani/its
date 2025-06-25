<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Employee;
use App\Models\webdesign;
use App\Models\Marketing;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Title('Employee Dashboard')]
// #[Layout('components.layouts.app')]
class EmployeeDashboard extends Component
{

  public function render()
  {
    return view('livewire.pages.employee-dashboard');
  }
}