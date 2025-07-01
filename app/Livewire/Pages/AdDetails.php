<?php

namespace App\Livewire\Pages;

use Livewire\Component;

use App\Models\Marketing;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AdDetails as ModelsAdDetails;

class AdDetails extends Component
{
    use WithPagination;

    public $adDetails;
    public $marketingId;
    public $clicks;
    public $calls;
    public $note;
    public $budgetSpent;
    public $performance;
    public $editingId;
    public $showModal = false;
    public $statsDate;
    public $ad;

    public function mount($id)
    {
        $this->marketingId = $id;
        // Check if user has permission to access this marketing campaign
        $this->authorizeAccess($id);
        
        $this->marketingId = $id;
        $this->adDetails = ModelsAdDetails::with('marketing')
            ->when($id, fn($q) => $q->where('marketing_id', $id))
            ->get();
        $this->ad = Marketing::where('id', $id)->get();
    }

    /**
     * Authorize user access to the marketing campaign
     */
    protected function authorizeAccess($marketingId)
    {
        $user = Auth::user();
        
        // Check if user is admin, manager, or customer support (can access all)
        $isAdminOrManagerOrSupport = method_exists($user, 'hasRole')
            ? ($user->hasRole('Admin') || $user->hasRole('Manager') || $user->hasRole('Customer Support'))
            : ($user->role === 'Admin' || $user->role === 'Manager' || $user->role === 'Customer Support' || $user->id === 1);
        
        if ($isAdminOrManagerOrSupport) {
            return; // Allow access
        }
        
        // For regular users, check if they own the marketing campaign
        $employee = \App\Models\Employee::where('user_id', $user->id)->first();
        
        if (!$employee) {
            abort(403, 'Access denied. Employee record not found.');
        }
        
        $marketing = Marketing::where('id', $marketingId)
            ->where('employee_id', $employee->id)
            ->first();
            
        if (!$marketing) {
            abort(403, 'Access denied. You can only view your own marketing campaigns.');
        }
    }

    public function save()
    {
        // Re-authorize before saving
        $this->authorizeAccess($this->marketingId);
        
        $this->validate([
            'marketingId' => 'required|exists:marketings,id',
            'clicks' => 'required|integer|min:0',
            'calls' => 'required|integer|min:0',
            'note' => 'nullable|string|max:255',
            'budgetSpent' => 'required|numeric|min:0',
            'statsDate' => 'required|date',
        ]);

        if ($this->editingId) {
            $adDetail =  ModelsAdDetails::with('marketing')->findOrFail($this->editingId);
            $adDetail->update([
                'marketing_id' => $this->marketingId,
                'clicks' => $this->clicks,
                'calls' => $this->calls,
                'note' => $this->note,
                'budget_spent' => $this->budgetSpent,
                'stats_date' => $this->statsDate,
            ]);
        } else {
             ModelsAdDetails::create([
                'marketing_id' => $this->marketingId,
                'clicks' => $this->clicks,
                'calls' => $this->calls,
                'note' => $this->note,
                'budget_spent' => $this->budgetSpent,
                'stats_date' => $this->statsDate,
            ]);
        }

        $this->resetForm();
        $this->showModal = false;
        $this->adDetails = ModelsAdDetails::with('marketing')
            ->when($this->marketingId, fn($q) => $q->where('marketing_id', $this->marketingId))
            ->get();
        $this->dispatch('showAlert', 'success', 'Ad detail saved!');
    }

    public function edit($id)
    {
        $this->mount($this->marketingId);
        // Re-authorize before editing
        $this->authorizeAccess($this->marketingId);
        
        $adDetail =  ModelsAdDetails::with('marketing')->findOrFail($id);
        $this->editingId = $adDetail->id;
        $this->marketingId = $adDetail->marketing_id;
        $this->clicks = $adDetail->clicks;
        $this->calls = $adDetail->calls;
        $this->note = $adDetail->note;
        $this->budgetSpent = $adDetail->budget_spent;
        $this->statsDate = $adDetail->stats_date;
        $this->showModal = true;
    }

    public function delete($id)
    {
        // Re-authorize before deleting
        $this->authorizeAccess($this->marketingId);
        
         ModelsAdDetails::with('marketing')->findOrFail($id)->delete();
        $this->adDetails = ModelsAdDetails::with('marketing')
            ->when($this->marketingId, fn($q) => $q->where('marketing_id', $this->marketingId))
            ->get();
        $this->dispatch('showAlert', 'success', 'Ad detail deleted!');
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'clicks', 'calls', 'note', 'budgetSpent', 'statsDate']);
    }

    public function popUp()
    {
       $this->mount($this->marketingId);
        $this->resetForm();
        $this->showModal = true;
    }

    public function popUpHide()
    {
        $this->mount($this->marketingId);
        $this->showModal = false;
        $this->resetForm();
    }

    #[Title('Ad Details')]
    public function render()
    {
        // Always eager load marketing in render as well
        // $this->adDetails = ModelsAdDetails::with('marketing')
        //     ->when($this->marketingId, fn($q) => $q->where('marketing_id', $this->marketingId))
        //     ->get();

        return view('livewire.pages.ad-details', [
            'adDetails' => $this->adDetails,
            'marketings' => $this->ad,
        ]);
    }
}