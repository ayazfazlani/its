<?php

namespace App\Livewire\Pages\Notices;

use Livewire\Component;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class NoticeForSpecific extends Component
{
  public $title = '';
  public $content = '';
  public $selectedUsers = [];
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
      'selectedUsers' => 'required|array|min:1',
    ]);

    if ($this->editingId) {
      $notice = Notice::findOrFail($this->editingId);
      $notice->update([
        'title' => $this->title,
        'content' => $this->content,
        'created_by' => Auth::id(),
        'target_type' => 'specific',
      ]);
      $notice->users()->sync($this->selectedUsers);
      $message = 'Notice updated for selected employees!';
    } else {
      $notice = Notice::create([
        'title' => $this->title,
        'content' => $this->content,
        'created_by' => Auth::id(),
        'target_type' => 'specific',
      ]);
      $notice->users()->attach($this->selectedUsers);
      $message = 'Notice sent to selected employees!';
    }


    $this->resetForm();
    $this->showModal = false;
  }

  public function edit($id)
  {
    $notice = Notice::with('users')->findOrFail($id);
    $this->editingId = $notice->id;
    $this->title = $notice->title;
    $this->content = $notice->content;
    $this->selectedUsers = $notice->users->pluck('id')->toArray();
    $this->showModal = true;
  }

  public function delete($id)
  {
    $notice = Notice::findOrFail($id);
    $notice->users()->detach();
    $notice->delete();

  }

  protected function resetForm()
  {
    $this->reset(['title', 'content', 'selectedUsers', 'editingId']);
  }

  public function render()
  {
    return view('livewire.pages.notices.notice-for-specific', [
      'users' => User::all(),
      'notices' => Notice::with('users')->where('target_type', 'specific')->latest()->get(),
    ]);
  }
}
