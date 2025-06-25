<?php

namespace App\Livewire\Pages;

use Livewire\Component;

use App\Models\Marketing;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
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

    public function mount($marketingId = null)
    {
        $this->marketingId = $marketingId;
        $this->adDetails = ModelsAdDetails::with('marketing')
            ->when($marketingId, fn($q) => $q->where('marketing_id', $marketingId))
            ->get();
    }

    public function save()
    {
        $this->validate([
            'marketingId' => 'required|exists:marketings,id',
            'clicks' => 'required|integer|min:0',
            'calls' => 'required|integer|min:0',
            'note' => 'nullable|string|max:255',
            'budgetSpent' => 'required|numeric|min:0',
        ]);

        if ($this->editingId) {
            $adDetail =  ModelsAdDetails::findOrFail($this->editingId);
            $adDetail->update([
                'marketing_id' => $this->marketingId,
                'clicks' => $this->clicks,
                'calls' => $this->calls,
                'note' => $this->note,
                'budget_spent' => $this->budgetSpent,
            ]);
        } else {
             ModelsAdDetails::create([
                'marketing_id' => $this->marketingId,
                'clicks' => $this->clicks,
                'calls' => $this->calls,
                'note' => $this->note,
                'budget_spent' => $this->budgetSpent,
            ]);
        }

        $this->resetForm();
        $this->showModal = false;
        $this->mount($this->marketingId);
        $this->dispatch('showAlert', 'success', 'Ad detail saved!');
    }

    public function edit($id)
    {
        $adDetail =  ModelsAdDetails::findOrFail($id);
        $this->editingId = $adDetail->id;
        $this->marketingId = $adDetail->marketing_id;
        $this->clicks = $adDetail->clicks;
        $this->calls = $adDetail->calls;
        $this->note = $adDetail->note;
        $this->budgetSpent = $adDetail->budget_spent;
        $this->showModal = true;
    }

    public function delete($id)
    {
         ModelsAdDetails::findOrFail($id)->delete();
        $this->mount($this->marketingId);
        $this->dispatch('showAlert', 'success', 'Ad detail deleted!');
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'clicks', 'calls', 'note', 'budgetSpent']);
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

    #[Title('Ad Details')]
    public function render()
    {
        // $adDetails = ModelsAdDetails::with('marketing')
        //     ->where('marketing_id', $this->marketingId)
        //     ->latest()
        //     ->paginate(10);

        return view('livewire.pages.ad-details', [
            'adDetails' => $this->adDetails,
            'marketings' => Marketing::all(),
        ]);
    }
}