<?php

namespace App\Livewire\Pages\Ads;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

class PauseAds extends Component
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
        $this->reloadData();
    }

    protected function reloadData()
    {
        $user = Auth::user();
        $isAdminOrManagerOrSupport = method_exists($user, 'hasRole')
            ? ($user->hasRole('Admin') || $user->hasRole('Manager') || $user->hasRole('Customer Support'))
            : ($user->role === 'Admin' || $user->role === 'Manager' || $user->role === 'Customer Support' || $user->id === 1);
        if ($isAdminOrManagerOrSupport) {
            $this->advertisements = Marketing::with(['employee.user'])->where('status', 'pause')->get();
            $this->employees = Employee::with('user')->get();
        } else {
            $employee = Employee::with('user')->where('user_id', Auth::id())->first();
            $this->advertisements = Marketing::with(['employee.user'])->where('status', 'pause')
                ->where('employee_id', $employee?->id)->get();
            $this->employees = Employee::with('user')->where('user_id', Auth::id())->get();
        }
        // Filter out ads with missing relationships
        $this->advertisements = $this->advertisements->filter(function ($ad) {
            return $ad->employee && $ad->employee->user;
        });
    }

    public function save()
    {
        $this->reloadData();
        $this->validate([
            'name' => 'required|string|max:255',
            'employeeId' => 'required|exists:employees,id',
            'webUrl' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'performance' => 'nullable',
            'status' => 'required|in:active,pause,inActive,clientLeft',
            'reason' => 'nullable|string|required_if:status,pause|required_if:status,clientLeft',
            'paymentStatus' => 'nullable|in:cleared,halfclear,uncleared',
            'paymentClearanceDate' => 'nullable|date'
        ]);

        $data = [
            'name' => $this->name,
            'employee_id' => $this->employeeId,
            'web_url' => $this->webUrl,
            'start_date' => $this->startDate,
            // 'end_date' => $this->endDate,
            'performance' => $this->performance,
            'reason' => $this->reason,
            'status' => $this->status,
            'payment_status' => $this->paymentStatus,
            'payment_clearance_date' => $this->paymentClearanceDate
        ];

        if ($this->editingId) {
            Marketing::findOrFail($this->editingId)->update($data);
            $message = 'Advertisement updated successfully!';
        } else {
            Marketing::create($data);
            $message = 'Advertisement created successfully!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->reloadData();
        $this->dispatch('showAlert', 'success', $message);
    }

    public function edit($id)
    {
        $ad = Marketing::with(['employee.user'])->findOrFail($id);
        $this->editingId = $ad->id;
        $this->marketingId = $ad->id;
        $this->name = $ad->name;
        $this->employeeId = $ad->employee_id;
        $this->webUrl = $ad->web_url;
        $this->startDate = $ad->start_date;
        // $this->endDate = $ad->end_date;
        $this->performance = $ad->performance;
        $this->reason = $ad->reason;
        $this->status = $ad->status;
        $this->paymentStatus = $ad->payment_status;
        $this->paymentClearanceDate = $ad->payment_clearance_date;
        $this->reloadData();
        $this->showModal = true;
    }

    public function delete($id)
    {
        Marketing::findOrFail($id)->delete();
        $this->reloadData();
        $this->dispatch('showAlert', 'success', 'Advertisement deleted successfully!');
    }

    protected function resetForm()
    {
        $this->reset([
            'name',
            'employeeId',
            'webUrl',
            'startDate',
            // 'endDate',
            'performance',
            'reason',
            'status',
            'marketingId',
            'editingId',
            'paymentStatus',
            'paymentClearanceDate'
        ]);
    }

    public function popUp()
    {
        $this->resetForm();
        $this->reloadData();
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->reloadData();
    }

    #[Title('Paused google Ads')]
    public function render()
    {
        $user = Auth::user();
        $isAdminOrManagerOrSupport = method_exists($user, 'hasRole')
            ? ($user->hasRole('Admin') || $user->hasRole('Manager') || $user->hasRole('Customer Support'))
            : ($user->role === 'Admin' || $user->role === 'Manager' || $user->role === 'Customer Support' || $user->id === 1);
        if ($isAdminOrManagerOrSupport) {
            $advertisements = Marketing::with(['employee.user'])->where('status', 'pause')->get();
            $employees = Employee::with('user')->get();
        } else {
            $employee = Employee::with('user')->where('user_id', Auth::id())->first();
            $advertisements = Marketing::with(['employee.user'])->where('status', 'pause')
                ->where('employee_id', $employee?->id)->get();
            $employees = Employee::with('user')->where('user_id', Auth::id())->get();
        }
        // Filter out ads with missing relationships
        $advertisements = $advertisements->filter(function ($ad) {
            return $ad->employee && $ad->employee->user;
        });
        return view('livewire.pages.ads.pause-ads', [
            'advertisements' => $advertisements,
            'employees' => $employees,
        ]);
    }
}