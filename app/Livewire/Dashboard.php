<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard')]
class Dashboard extends Component
{
    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
