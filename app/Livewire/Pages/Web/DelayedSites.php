<?php

namespace App\Livewire\Pages\Web;

use Livewire\Component;
use App\Models\Employee;
use App\Models\webdesign;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DelayedSites extends Component
{

    use WithPagination;

    public $editingId = null;
    public $employeeId;
    public $projectName;
    public $websiteUrl;
    public $category;
    public $status;
    public $description;
    public $toolsUsed;
    public $startDate;
    public $endDate;
    public $performance = 0;
    public $reason;
    public $employees;
    public $websites;
    public $showModal = false;

    protected $rules = [
        'employeeId' => 'required|exists:employees,id',
        'projectName' => 'required|max:255',
        'websiteUrl' => 'nullable|url',
        'category' => 'required|in:Business,E-Commerce,Portfolio,Blog,Other',
        'status' => 'required|in:in progress,delivered,in review,delayed',
        'description' => 'nullable|string',
        'toolsUsed' => 'nullable|string',
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
        'performance' => 'nullable|numeric|min:0|max:100',
        'reason' => 'nullable|string|required_if:status,cancelled'
    ];

    public function mount()
    {

        $this->loadWebsites();
    }

    public function loadWebsites()
    {
        $user = Auth::user();
        // Replace hasRole with a generic role check (implement your own logic as needed)
        $isAdminOrManager = method_exists($user, 'hasRole') ? ($user->hasRole('Admin') || $user->hasRole('Manager')) : ($user->role === 'Admin' || $user->role === 'Manager' || $user->id === 1);
        if ($isAdminOrManager) {
            $this->employees = Employee::with('user')->get();
            $this->websites = Webdesign::with(['employee.user'])
                ->where('status', 'delayed')
                ->get();
        } else {
            $this->employees = Employee::where('user_id', Auth::id())->get();
            $employee = Employee::where('user_id', Auth::id())->first();
            $this->websites = Webdesign::with(['employee.user'])
                ->where('status', 'delayed')
                ->where('employee_id', $employee->id ?? 0)
                ->get();
        }
    }

    public function popUp()
    {
        $this->resetValidation();
        $this->reset(['editingId', 'employeeId', 'projectName', 'websiteUrl', 'category', 'status', 'description', 'toolsUsed', 'startDate', 'endDate', 'performance', 'reason']);
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'employee_id' => $this->employeeId,
            'project_name' => $this->projectName,
            'website_url' => $this->websiteUrl,
            'category' => $this->category,
            'status' => $this->status,
            'description' => $this->description,
            'tools_used' => $this->toolsUsed,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'performance' => $this->performance,
            'reason' => $this->reason
        ];

        if ($this->editingId) {
            Webdesign::find($this->editingId)->update($data);
            session()->flash('message', 'Website updated successfully.');
            $this->mount();
        } else {
            Webdesign::create($data);
            session()->flash('message', 'Website added successfully.');
            $this->mount();
        }

        $this->popUpHide();
    }

    public function edit($id)
    {
        $website = Webdesign::findOrFail($id);
        $this->editingId = $id;
        $this->employeeId = $website->employee_id;
        $this->projectName = $website->project_name;
        $this->websiteUrl = $website->website_url;
        $this->category = $website->category;
        $this->status = $website->status;
        $this->description = $website->description;
        $this->toolsUsed = $website->tools_used;
        $this->startDate = $website->start_date;
        $this->endDate = $website->end_date;
        $this->performance = $website->performance;
        $this->reason = $website->reason;

        $this->showModal = true;
    }

    public function delete($id)
    {
        Webdesign::find($id)->delete();
        session()->flash('message', 'Website deleted successfully.');
        $this->mount();
    }

    public function render()
    {
        return view('livewire.pages.web.delayed-sites');
    }
}