<?php

namespace App\Livewire\Pages\Notices;

use App\Models\User;
use App\Models\Notice;
use Livewire\Component;
use App\Models\NoticeUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Notice For Specific')]
class NoticeForSpecific extends Component
{
  public $title = '';
  public $content = '';
  public $selectedUsers = [];
  public $showModal = false;
  public $editingId = null;
  public $notice;

  public function mount()
  {
    if (auth()->user()->hasRole(['Manager', 'Admin'])) {
      $this->notice = Notice::with('users')->where('target_type', 'specific')->latest()->get();
    } else {
      // notice for me like i am employee i want to see my notices
      $this->notice = NoticeUser::with('notice')->where('user_id', auth()->user()->id)->latest()->get();
    }
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
      'notices' => $this->notice,
    ]);
  }
}
