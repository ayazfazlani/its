<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

#[Title('Users Management')]
class UsersCrud extends Component
{
  use WithPagination;

  public $search = '';
  public $perPage = 10;

  public $showModal = false;
  public $isEdit = false;
  public $userId = null;
  public $name = '';
  public $email = '';
  public $password = '';
  public $password_confirmation = '';
  public $selectedRoles = [];

  public $showDeleteModal = false;
  public $deleteId = null;

  protected function rules()
  {
    $rules = [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email' . ($this->isEdit && $this->userId ? ',' . $this->userId : ''),
      'selectedRoles' => 'array',
    ];
    if (!$this->isEdit) {
      $rules['password'] = 'required|string|min:6|confirmed';
    }
    return $rules;
  }

  public function render()
  {
    $users = User::with('roles')
      ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
      ->orderBy('id', 'desc')
      ->paginate($this->perPage);
    $roles = Role::all();
    return view('livewire.admin.users-crud', [
      'users' => $users,
      'roles' => $roles,
    ]);
  }

  public function openCreateModal()
  {
    $this->resetForm();
    $this->isEdit = false;
    $this->showModal = true;
  }

  public function openEditModal($id)
  {
    $user = User::with('roles')->findOrFail($id);
    $this->userId = $user->id;
    $this->name = $user->name;
    $this->email = $user->email;
    $this->selectedRoles = $user->roles->pluck('id')->toArray();
    $this->isEdit = true;
    $this->showModal = true;
  }

  public function saveUser()
  {
    $this->validate();

    // Debug: Log what's being passed
    \Log::info('UsersCrud saveUser - selectedRoles before processing:', $this->selectedRoles);

    // Ensure selectedRoles contains only valid role IDs and convert to integers
    $validRoleIds = Role::pluck('id')->toArray();
    \Log::info('UsersCrud saveUser - validRoleIds:', $validRoleIds);

    $this->selectedRoles = array_filter($this->selectedRoles, function ($roleId) use ($validRoleIds) {
      return in_array((int)$roleId, $validRoleIds);
    });

    // Convert all role IDs to integers
    $roleIds = array_map('intval', $this->selectedRoles);

    \Log::info('UsersCrud saveUser - roleIds after processing:', $roleIds);

    // Get role names for fallback
    $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
    \Log::info('UsersCrud saveUser - roleNames for fallback:', $roleNames);

    if ($this->isEdit && $this->userId) {
      $user = User::findOrFail($this->userId);
      try {
        // Try with role IDs first
        $user->syncRoles($roleIds);
      } catch (\Exception $e) {
        \Log::error('UsersCrud saveUser - Error with role IDs, trying with names:', ['error' => $e->getMessage()]);
        // Fallback to role names
        $user->syncRoles($roleNames);
      }
    } else {
      $user = User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
      ]);
      try {
        // Try with role IDs first
        $user->syncRoles($roleIds);
      } catch (\Exception $e) {
        \Log::error('UsersCrud saveUser - Error with role IDs, trying with names:', ['error' => $e->getMessage()]);
        // Fallback to role names
        $user->syncRoles($roleNames);
      }
    }
    $this->showModal = false;
    $this->resetForm();
    session()->flash('message', $this->isEdit ? 'User roles updated.' : 'User created.');
  }

  public function confirmDelete($id)
  {
    $this->deleteId = $id;
    $this->showDeleteModal = true;
  }

  public function deleteUser()
  {
    $user = User::findOrFail($this->deleteId);
    $user->delete();
    $this->showDeleteModal = false;
    $this->deleteId = null;
    session()->flash('message', 'User deleted.');
  }

  public function resetForm()
  {
    $this->userId = null;
    $this->name = '';
    $this->email = '';
    $this->password = '';
    $this->password_confirmation = '';
    $this->selectedRoles = [];
  }

  public function updatingSearch()
  {
    $this->resetPage();
  }
  public function updatingPerPage()
  {
    $this->resetPage();
  }
}
