<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;


#[Title('Lahore Rehab Center')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.pages.home');
    }
}
