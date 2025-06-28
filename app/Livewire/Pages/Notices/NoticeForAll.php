<?php

namespace App\Livewire\Pages\Notices;

use Livewire\Component;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Notice For All')]
class NoticeForAll extends Component
{
    public $title = '';
    public $content = '';
    public $showModal = false;
    public $editingId = null;

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

    public function submit()
    {
        $this->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($this->editingId) {
            $notice = Notice::findOrFail($this->editingId);
            $notice->update([
                'title' => $this->title,
                'content' => $this->content,
                'created_by' => Auth::id(),
                'target_type' => 'all',
            ]);
            $message = 'Notice updated for all employees!';
        } else {
            Notice::create([
                'title' => $this->title,
                'content' => $this->content,
                'created_by' => Auth::id(),
                'target_type' => 'all',
            ]);
            $message = 'Notice sent to all employees!';
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        $this->editingId = $notice->id;
        $this->title = $notice->title;
        $this->content = $notice->content;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();
    }

    protected function resetForm()
    {
        $this->reset(['title', 'content', 'editingId']);
    }

    public function render()
    {
        return view('livewire.pages.notices.notice-for-all', [
            'notices' => Notice::where('target_type', 'all')->latest()->get(),
        ]);
    }
}
