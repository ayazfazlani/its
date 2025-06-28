<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Home')]
class Home extends Component
{
    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.home');
    }
}
