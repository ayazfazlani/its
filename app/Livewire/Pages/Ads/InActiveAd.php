<?php

namespace App\Livewire\Pages\Ads;

use Livewire\Component;
use App\Models\Employee;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

class InActiveAd extends Component
{
    public $advertisements;
    public $name;
    public $employeeId;
    public $webUrl;
    public $startDate;
    public $endDate;
    public $performance = 0;
    public $reason;
    public $status;
    public $marketingId;
    public $editingId;
    public $employees;
    public $paymentStatus;
    public $paymentClearanceDate;
    public $showModal = false;

    public function mount()
    {
        $employee = Employee::with('user')->where('user_id', Auth::id())->first();
        $user = Auth::user();
        $isAdminOrManagerOrSupport = method_exists($user, 'hasRole')
            ? ($user->hasRole('Admin') || $user->hasRole('Manager') || $user->hasRole('Customer Support'))
            : ($user->role === 'Admin' || $user->role === 'Manager' || $user->role === 'Customer Support' || $user->id === 1);
        if ($isAdminOrManagerOrSupport) {
            $this->advertisements = Marketing::with(['employee.user'])->where('status', 'inActive')->get();
            $this->employees = Employee::with('user')->get();
        } else {
            $this->advertisements = Marketing::with(['employee.user'])->where('status', 'inActive')
                ->where('employee_id', $employee?->id)->get();
            $this->employees = Employee::with('user')->where('user_id', Auth::id())->get();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'employeeId' => 'required|exists:employees,id',
            'webUrl' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'performance' => 'nullable',
            'status' => 'required|in:active,pause,inActive,clientLeft',
            'reason' => 'nullable|string|required_if:status,pause|required_if:status,clientLeft',
        ]);

        if ($this->editingId) {
            $ad = Marketing::findOrFail($this->editingId);
            $ad->update([
                'name' => $this->name,
                'employee_id' => $this->employeeId,
                'web_url' => $this->webUrl,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'performance' => $this->performance,
                'reason' => $this->reason,
                'status' => $this->status
            ]);
            $message = 'Advertisement updated successfully!';
        } else {
            Marketing::create([
                'name' => $this->name,
                'employee_id' => $this->employeeId,
                'web_url' => $this->webUrl,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'performance' => $this->performance,
                'reason' => $this->reason,
                'status' => $this->status
            ]);
            $message = 'Advertisement created successfully!';
        }

        $this->resetForm();
        $this->dispatch('pop');
        $this->dispatch('showAlert', 'success', $message);

        $this->mount();
    }

    public function edit($id)
    {
        $ad = Marketing::with(['employee.user'])->findOrFail($id);
        // dd($ad);
        $this->editingId = $id;
        $this->marketingId = $ad->id;
        $this->name = $ad->name;
        $this->employeeId = $ad->employee_id;
        $this->webUrl = $ad->web_url;
        $this->startDate = $ad->start_date;
        $this->endDate = $ad->end_date;
        $this->performance = $ad->performance;
        $this->reason = $ad->reason;
        $this->status = $ad->status;

        $this->showModal = true;
    }

    public function delete($id)
    {
        $ad = Marketing::findOrFail($id);
        $ad->delete();

        $this->dispatch('showAlert', 'success', 'Advertisement deleted successfully!');

        $this->mount();
    }

    protected function resetForm()
    {
        $this->reset([
            'name',
            'employeeId',
            'webUrl',
            'startDate',
            'endDate',
            'performance',
            'reason',
            'status',
            'marketingId',
            'editingId'
        ]);
    }

    public function popUp()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    #[Title('Overdue google Ads')]
    public function render()
    {
        return view('livewire.pages.ads.in-active-ad', [
            'advertisements' => $this->advertisements,
            'employees' => $this->employees
        ]);
    }
}