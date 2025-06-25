<?php

namespace App\Livewire\Pages;

use App\Models\Branch;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

use Livewire\Attributes\Title;

class Branches extends Component
{
    public $name, $location, $branchId;
    public $showModal = false;

    public function save()
    {
        // Validate the input data
        $this->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        // Attempt to create the branch
        Branch::create([
            'name' => $this->name,
            'location' => $this->location,
        ]);
        $this->reset(['name', 'location']);
        $this->showModal = false;
        $this->dispatch('showAlert',  'success', 'Branch created successfully!');
    }

    public function delete($id)
    {
        $branch = Branch::find($id);

        if ($branch) {
            $branch->delete();
            $this->dispatch('showAlert',  'warning', 'Branch deleted successfully!');
        } else {
            $this->dispatch('showAlert',  'warning', 'There was issue while deleting the Branch!');
        }
    }

    public function edit($id)
    {
        $branch = Branch::find($id);
        $this->name = $branch->name;
        $this->location = $branch->location;
        $this->branchId = $branch->id;
        $this->showModal = true;
    }

    public function update()
    {
        // Validate the input data
        $validated = $this->validate([
            'name' => 'required|min:6',
            'location' => 'required|min:3',
        ]);

        // Attempt to create the branch
        try {
            $branch = Branch::find($this->branchId);
            $branch->update($validated);
            $this->reset(['name', 'location']);
            $this->showModal = false;
            $this->dispatch('showAlert',  'success', 'Branch updated successfully!');
        } catch (\Exception $e) {
            // Handle any exceptions
            $this->dispatch('showAlert', 'error', 'Failed to updated branch!');
        }
    }

    public function popUp()
    {
        $this->reset(['name', 'location', 'branchId']);
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
        $this->reset(['name', 'location', 'branchId']);
    }

    #[Title('Branches')]
    public function render()
    {
        return view('livewire.pages.branches', [
            'branches' => Branch::all(),
        ]);
    }
}