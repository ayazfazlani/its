<?php

namespace App\Livewire\Admin\ViewAds;

use Livewire\Component;
use App\Models\Employee;
use App\Models\webdesign;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class EmpWebDevAds extends Component
{
    public $advertisements;
    public $employees;
    public $showModal = false;
    public $editingId;
    public $name;
    public $employeeId;
    public $webUrl;
    public $startDate;
    public $endDate;
    public $performance = 0;
    public $reason;
    public $status;
    public $paymentStatus;
    public $paymentClearanceDate;
    public $empId;
    public $filterStatus = 'active'; // Default status filter

    public function mount($id, $status = 'active')
    {
        $this->empId = $id;
        $this->filterStatus = $status;
        $this->reloadData($this->empId);
    }

    protected function reloadData($id)
    {
        $user = Auth::user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        if($isAdminOrManager){
            $this->advertisements = webdesign::with('employee.user')
                ->whereHas('employee', function($query) use ($id) {
                    $query->where('id', $id);
                })
                ->where('status', $this->filterStatus)
                ->get();
            $this->employees = Employee::with('user')->get();
        }else{
            abort(403,'not allowed to access the page');
        }
        $this->advertisements = $this->advertisements->filter(function ($ad) {
            return $ad->employee && $ad->employee->user;
        });
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'employeeId' => 'required|exists:employees,id',
            'webUrl' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'performance' => 'nullable',
            'status' => 'required|in:active,pause,inActive,clientLeft',
            'reason' => 'nullable|string|required_if:status,pause|required_if:status,clientLeft',
        ]);

        $data = [
            'name' => $this->name,
            'employee_id' => $this->employeeId,
            'web_url' => $this->webUrl,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'performance' => $this->performance,
            'reason' => $this->reason,
            'status' => $this->status,
            'payment_status' => $this->paymentStatus,
            'payment_clearance_date' => $this->paymentClearanceDate
        ];

        if ($this->editingId) {
            webdesign::findOrFail($this->editingId)->update($data);
            $message = 'Web Ad updated successfully!';
        } else {
            webdesign::create($data);
            $message = 'Web Ad created successfully!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->reloadData($this->empId);
        $this->dispatch('showAlert', 'success', $message);
    }

    public function edit($id)
    {
        $ad = webdesign::with('employee.user')->findOrFail($id);
        $this->editingId = $ad->id;
        $this->name = $ad->name;
        $this->employeeId = $ad->employee_id;
        $this->webUrl = $ad->web_url;
        $this->startDate = $ad->start_date;
        $this->endDate = $ad->end_date;
        $this->performance = $ad->performance;
        $this->reason = $ad->reason;
        $this->status = $ad->status;
        $this->paymentStatus = $ad->payment_status;
        $this->paymentClearanceDate = $ad->payment_clearance_date;
        $this->reloadData($this->empId);
        $this->showModal = true;
    }

    public function delete($id)
    {
        webdesign::findOrFail($id)->delete();
        $this->reloadData($this->empId);
        $this->dispatch('showAlert', 'success', 'Web Ad deleted successfully!');
    }

    protected function resetForm()
    {
        $this->reset([
            'name', 'employeeId', 'webUrl', 'startDate', 'endDate', 'performance', 'reason', 'status', 'editingId', 'paymentStatus', 'paymentClearanceDate'
        ]);
    }

    public function popUp()
    {
        $this->resetForm();
        $this->reloadData($this->empId);
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->reloadData($this->empId);
    }

    public function filterByStatus($status)
    {
        $this->filterStatus = $status;
        $this->reloadData($this->empId);
        $this->dispatch('url', url()->route('webdev.employee.view', ['id' => $this->empId, 'status' => $status]));
    }

    public function getTitleProperty()
    {
        return match($this->filterStatus) {
            'active' => 'Active Web Projects',
            'pause' => 'Paused Web Projects', 
            'inActive' => 'Overdue Web Projects',
            'clientLeft' => 'Client Left Web Projects',
            default => 'Web Projects'
        };
    }

    public function render()
    {
        $user = Auth::user();
        $isAdminOrManager = $user->hasRole(['Admin', 'Manager']);
        if ($isAdminOrManager) {
            $advertisements = webdesign::with('employee.user')->where('status', $this->filterStatus)->get();
            $employees = Employee::get();
        } else {
            $employee = Employee::where('user_id', $user->id)->first();
            $advertisements = webdesign::with('employee.user')
                ->where('status', $this->filterStatus)
                ->where('employee_id', $employee?->id)
                ->get();
            $employees = Employee::with('user')->where('user_id', $user->id)->get();
        }
        $advertisements = $advertisements->filter(function ($ad) {
            return $ad->employee && $ad->employee->user;
        });
        return view('livewire.admin.view-ads.emp-webdev-ads', [
            'advertisements' => $advertisements,
            'employees' => $employees,
        ]);
    }
} 