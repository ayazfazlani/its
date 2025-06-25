<?php

namespace App\Livewire\Pages\Notices;

use Livewire\Component;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;


class NoticeList extends Component
{
  public $notices;
  public $title = '';
  public $content = '';
  public $showModal = false;
  public $editingId = null;

  public function mount()
  {
    $this->loadNotices();
  }

  public function loadNotices()
  {
    $userId = Auth::id();
    $this->notices = Notice::where('target_type', 'all')
      ->orWhereHas('users', function ($q) use ($userId) {
        $q->where('users.id', $userId);
      })
      ->latest()
      ->get();
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
      ]);
      $message = 'Notice updated!';
    } else {
      Notice::create([
        'title' => $this->title,
        'content' => $this->content,
        'created_by' => Auth::id(),
        'target_type' => 'all',
      ]);
      $message = 'Notice created!';
    }


    $this->resetForm();
    $this->showModal = false;
    $this->loadNotices();
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

    $this->loadNotices();
  }

  protected function resetForm()
  {
    $this->reset(['title', 'content', 'editingId']);
  }

  public function render()
  {
    return view('livewire.pages.notices.notice-list');
  }
}