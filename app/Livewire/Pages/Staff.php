<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Models\Branch;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;


#[Title('Staff')]
class Staff extends Component
{
    public $name, $email, $branch_id, $password;
    public $staffs, $branches, $selectedStaff;

    public function mount()
    {
        if (Auth::user()->hasRole(['Admin'])) {
            $this->branches = Branch::all(); // Fetch all branches

            $this->staffs = User::with('branch')->get(); // Fetch all staffs with their branch
        } elseif (Auth::user()->hasRole('Manager')) {
            $this->branches = Branch::where('id', Auth::user()->branch_id)->get();
            $this->staffs = User::where('branch_id', Auth::user()->branch_id)->get();
        } else {
            $this->branches = Branch::all(); // Fetch all branches

            $this->staffs = User::with('branch')->get(); // Fetch all staffs with their branch
        }
    }
    public function store()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'branch_id' => 'required|exists:branches,id',
            'password' => 'required|min:6',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'branch_id' => $this->branch_id,
            'password' => Hash::make($this->password),
        ];

        // dd($data);

        User::create($data);

        $this->dispatch('pop');
        // Dispatch success alert and use Toaster for notification
        $this->dispatch('showAlert',  'success', 'Staff added successfully!');
        $this->reset();
        $this->mount(); // Refresh data
    }

    public function edit($id)
    {
        $this->dispatch('popUpShow');
        $this->selectedStaff = User::find($id);
        $this->name = $this->selectedStaff->name;
        $this->email = $this->selectedStaff->email;
        $this->branch_id = $this->selectedStaff->branch_id;
        $this->password = '';
    }
    public function update()
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'branch_id' => $this->branch_id,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->selectedStaff->update($data);

        $this->dispatch('pop');
        // Dispatch success alert and use Toaster for notification
        $this->dispatch('showAlert',  'success', 'Staff updated successfully!');
        $this->reset();
        $this->mount(); // Refresh data
    }

    public function delete($id)
    {
        User::find($id)->delete();
        $this->dispatch('pop');
        // Dispatch success alert and use Toaster for notification
        $this->dispatch('showAlert',  'success', 'Staff deleted successfully!');
        $this->reset();
        $this->mount(); // Refresh data
    }


    public function popUp()
    {
        $this->popUpHide();
        $this->dispatch('popUpShow');
    }
    public function popUpHide()
    {
        $this->dispatch('pop');
        $this->reset(['name', 'email', 'password', 'branch_id', 'selectedStaff']);
    }
    public function render()
    {
        return view('livewire.pages.staff', [
            'branches' => $this->branches,
            'staffs' => $this->staffs,
        ]);
    }
}