<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Employee;
use App\Models\webdesign as Webdesign;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Web Developers')]
class WebDevelopers extends Component
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
        // TODO: Replace with your own role logic if you have a role system
        $user = Auth::user();
        $isAdminOrManager = false;
        // Example: if you use Spatie roles, uncomment the next line
        $isAdminOrManager = $user && ($user->hasRole('Admin') || $user->hasRole('Manager'));
        // Or fallback to user IDs or another check
        // $isAdminOrManager = $user && in_array($user->id, [1,2]);

        if ($isAdminOrManager) {
            $this->employees = Employee::with('user')->get();
            $this->websites = Webdesign::with(['employee.user'])
                ->where('status', 'in progress')
                ->get();
        } else {
            $this->employees = Employee::where('user_id', Auth::id())->get();
            $employee = Employee::where('user_id', Auth::id())->first();
            $this->websites = Webdesign::with(['employee.user'])
                ->where('status', 'in progress')
                ->where('employee_id', $employee ? $employee->id : null)
                ->get();
        }
    }

    public function popUp()
    {
        $this->resetValidation();
        $this->reset(['editingId', 'employeeId', 'projectName', 'websiteUrl', 'category', 'status', 'description', 'toolsUsed', 'startDate', 'endDate', 'performance', 'reason']);
        $user = Auth::user();
        $isAdminOrManager = $user && ($user->hasRole('Admin') || $user->hasRole('Manager'));
        if ($isAdminOrManager) {
            $this->employees = Employee::with('user')->get();
        } else {
            $this->employees = Employee::with('user')->where('user_id', Auth::id())->get();
        }
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
        $user = Auth::user();
        $isAdminOrManager = $user && ($user->hasRole('Admin') || $user->hasRole('Manager'));
        if ($isAdminOrManager) {
            $this->employees = Employee::with('user')->get();
        } else {
            $this->employees = Employee::with('user')->where('user_id', Auth::id())->get();
        }
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
        return view('livewire.pages.web-developers');
    }
}
