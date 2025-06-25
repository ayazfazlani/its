<?php

namespace App\Livewire\Pages\CstmrSprt;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Marketing;
use Livewire\Attributes\Title;

use Illuminate\Support\Facades\Auth;

class PaymentHalfCleared extends Component
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
        $employee = Employee::where('user_id', Auth::id())->first();
        $user = Auth::user();
        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('Admin') : ($user->role ?? null) === 'Admin' || $user->id === 1;
        $isCstmrSupport = method_exists($user, 'hasRole') ? $user->hasRole('Customer Support') : ($user->role ?? null) === 'Customer Support';
        if ($isAdmin || $isCstmrSupport) {
            $this->advertisements = Marketing::with('employee.user')->where('payment_status', 'halfclear')->get();
            $this->employees = Employee::with('user')->get();
        } else {
            $this->advertisements = Marketing::with('employee.user')->where('payment_status', 'halfclear')
                ->where('employee_id', $employee->id)->get();
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
            'paymentStatus' => 'nullable|in:cleared,halfclear,uncleared',
            'paymentClearanceDate' => 'nullable|date',
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
                'status' => $this->status,
                'payment_status' => $this->paymentStatus,
                'paymentClearanceDate' => $this->paymentClearanceDate,
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
                'status' => $this->status,
                'payment_status' => $this->paymentStatus,
                'paymentClearanceDate' => $this->paymentClearanceDate,
            ]);
            $message = 'Advertisement created successfully!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('showAlert', 'success', $message);

        $this->mount();
    }

    public function edit($id)
    {
        $ad = Marketing::findOrFail($id);
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
        $this->paymentStatus = $ad->payment_status;
        $this->paymentClearanceDate = $ad->paymentClearanceDate;
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
            'editingId',
            'paymentStatus',
            'paymentClearanceDate',
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

    #[Title('Payment Half Clear')]
    public function render()
    {
        return view('livewire.pages.cstmr-sprt.payment-half-cleared');
    }
}