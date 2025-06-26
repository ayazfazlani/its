<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Models\Branch;
use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Title;


class EmployeeProfile extends Component
{
    public $userId, $branchId, $position, $department, $joiningDate, $cnic, $phone, $address, $status;
    public $employeeId;
    public $branches;
    public $users;
    public $showModal = false;

    public function mount()
    {
        $this->branches = Branch::all();
        $this->users = User::all();
    }

    public function create()
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
            'branchId' => 'required|exists:branches,id',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'joiningDate' => 'required|date',
            'employeeId' => 'nullable',
            'cnic' => 'required|string|min:10|unique:employees,cnic',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        // Generate proper employee ID (better than rand()
        $data = [
            'user_id' => $this->userId,
            'branch_id' => $this->branchId,
            'department' => $this->department,
            'position' => $this->position,
            'joining_date' => $this->joiningDate,
            'cnic' => $this->cnic,
            'employee_id' => $this->employeeId,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status
        ];
        // dd($data);
        Employee::create($data);
        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('showAlert',  'success', 'Employee added successfully!');
    }


    protected function resetForm()
    {
        $this->reset([
            'userId',
            'branchId',
            'department',
            'position',
            'joiningDate',
            'cnic',
            'phone',
            'address'
        ]);
    }
    public function popUp()
    {
        $this->resetForm();
        $this->employeeId = null;
        $this->showModal = true;
    }
    public function popUpHide()
    {
        $this->showModal = false;
        $this->resetForm();
    }


    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $employee->id;
        $this->userId = $employee->user_id;
        $this->branchId = $employee->branch_id;
        $this->department = $employee->department;
        $this->position = $employee->position;
        $this->joiningDate = $employee->joining_date;
        $this->cnic = $employee->cnic;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->status = $employee->status;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
            'branchId' => 'required|exists:branches,id',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'joiningDate' => 'required|date',
            'cnic' => 'required|string|min:10|unique:employees,cnic,' . $this->employeeId,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $employee = Employee::findOrFail($this->employeeId);
        $employee->update([
            'user_id' => $this->userId,
            'branch_id' => $this->branchId,
            'department' => $this->department,
            'position' => $this->position,
            'joining_date' => $this->joiningDate,
            'cnic' => $this->cnic,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status
        ]);

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('showAlert', 'success', 'Employee updated successfully!');
    }

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        $this->dispatch('showAlert', 'success', 'Employee deleted successfully!');
    }

    #[Title('Employees List')]

    public function render()
    {
        return view('livewire.pages.employee-profile', [
            'branches' => $this->branches,
            'users' => $this->users,
            'employees' => Employee::with('user', 'branch')->get(),
        ]);
    }
}