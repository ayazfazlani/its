<?php

namespace App\Livewire\Admin\ViewAds;

use Livewire\Component;
use App\Models\Employee;
use App\Models\webdesign;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class EmpWebDevSites extends Component
{
    public $employee;
    public $projects;
    public $empId;
    public $filterStatus = 'in progress'; // Default status
    public $statuses = [
        'in progress' => 'In Progress',
        'in review' => 'In Review',
        'delivered' => 'Delivered',
        'delayed' => 'Delayed',
    ];

    // Modal fields
    public $editingId = null;
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
    public $showModal = false;

    protected $rules = [
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

    public function mount($id, $status = 'in progress')
    {
        $this->empId = $id;
        $this->filterStatus = $status;
        $this->employee = Employee::with('user')->findOrFail($id);
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $this->projects = webdesign::where('employee_id', $this->empId)
            ->where('status', $this->filterStatus)
            ->get();
    }

    public function filterByStatus($status)
    {
        $this->filterStatus = $status;
        $this->loadProjects();
        $this->dispatch('url', url()->route('webdev.employee.sites', ['id' => $this->empId, 'status' => $status]));
    }

    public function popUp()
    {
        $this->resetValidation();
        $this->reset(['editingId', 'projectName', 'websiteUrl', 'category', 'status', 'description', 'toolsUsed', 'startDate', 'endDate', 'performance', 'reason']);
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->reset(['editingId', 'projectName', 'websiteUrl', 'category', 'status', 'description', 'toolsUsed', 'startDate', 'endDate', 'performance', 'reason']);
    }

    public function save()
    {
        $this->validate();
        $data = [
            'employee_id' => $this->empId,
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
            webdesign::find($this->editingId)->update($data);
            session()->flash('message', 'Website updated successfully.');
        } else {
            webdesign::create($data);
            session()->flash('message', 'Website added successfully.');
        }
        $this->popUpHide();
        $this->loadProjects();
    }

    public function edit($id)
    {
        $website = webdesign::findOrFail($id);
        $this->editingId = $id;
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
        webdesign::find($id)->delete();
        session()->flash('message', 'Website deleted successfully.');
        $this->loadProjects();
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.view-ads.emp-webdev-sites', [
            'employee' => $this->employee,
            'projects' => $this->projects,
            'statuses' => $this->statuses,
            'filterStatus' => $this->filterStatus,
            'showModal' => $this->showModal,
            'editingId' => $this->editingId,
            'projectName' => $this->projectName,
            'websiteUrl' => $this->websiteUrl,
            'category' => $this->category,
            'status' => $this->status,
            'description' => $this->description,
            'toolsUsed' => $this->toolsUsed,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'performance' => $this->performance,
            'reason' => $this->reason,
        ]);
    }
} 