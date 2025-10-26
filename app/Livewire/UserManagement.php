<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $password;
    public $role;
    public $userId;
    public $isEditMode = false;
    public $search = '';
    public $roleFilter = 'all';
    public $perPage = 10;

    // Modal states
    public $showModal = false;
    public $showDeleteConfirmation = false;
    public $deletingUserName = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'role' => 'required|exists:roles,name',
    ];

    protected $messages = [
        'role.required' => 'Role harus dipilih',
        'role.exists' => 'Role tidak valid',
    ];

    protected $listeners = ['deleteUser' => 'delete'];

    public function render()
    {
        $this->authorize('manage-users');

        // Start with query
        $query = User::with('roles')->orderBy('created_at', 'desc');

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Apply role filter
        if ($this->roleFilter !== 'all') {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->paginate($this->perPage);
        $roles = Role::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store()
    {
        $this->authorize('manage-users');

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        $this->resetForm();
        $this->dispatch('userSaved');
    }

    public function edit($id)
    {
        $this->authorize('manage-users');

        $user = User::find($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()->name ?? null;
        $this->password = null;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        return $this->isEditMode ? $this->update() : $this->store();
    }

    public function saveProductionModal()
    {
        return $this->save();
    }

    public function update()
    {
        $this->authorize('manage-users');

        $user = User::find($this->userId);

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        // Sync role
        $user->syncRoles([$this->role]);

        $this->resetForm();
        $this->isEditMode = false;
        $this->dispatch('userUpdated');
    }

    public function delete($id)
    {
        $this->authorize('manage-users');

        $user = User::find($id);
        $user->delete();
        $this->dispatch('userDeleted');
    }

    public function resetForm()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->role = null;
        $this->userId = null;
        $this->isEditMode = false;

        // Reset modal states
        $this->showModal = false;
        $this->showDeleteConfirmation = false;
        $this->deletingUserName = '';

        // Reset validation rules
        $this->rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function confirmDelete($id, $userName)
    {
        $this->deletingUserName = $userName;
        $this->showDeleteConfirmation = true;
    }

    public function deleteConfirmed()
    {
        // Find user by name (storing in modal)
        $user = User::where('name', $this->deletingUserName)->first();
        if ($user) {
            $user->delete();
            $this->dispatch('userDeleted');
            $this->showDeleteConfirmation = false;
            $this->deletingUserName = '';
        }
    }
}