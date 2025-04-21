<?php
namespace App\Livewire\CRUD;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserDetail extends Component
{
    public User $user;

    public string $password = '';
    public string $password_confirmation = '';

    public array $selectedRoles = [];
    public array $selectedPermissions = [];

    public $allRoles;
    public $allPermissions;

    public function mount()
    {
        $this->allRoles = Role::all();
        $this->allPermissions = Permission::all();

        $this->selectedRoles = $this->user->roles->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->selectedPermissions = $this->user->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $this->user->password = Hash::make($this->password);
        $this->user->save();

        session()->flash('success', 'Пароль успешно обновлён!');
        $this->reset(['password', 'password_confirmation']);
    }

    public function updateRolesAndPermissions()
    {
        $permission = Permission::where('slug', 'assign-roles-to-users')->first();
        if (auth()->user()->hasPermissionTo($permission)) {
            $this->user->roles()->sync($this->selectedRoles);
        }else{
            abort(403);
        }

        $permission = Permission::where('slug', 'assign-roles-to-users')->first();
        if (auth()->user()->hasPermissionTo($permission)) {
            $slugs = Permission::whereIn('id', $this->selectedPermissions)->pluck('slug')->toArray();
            $this->user->refreshPermissions(...$slugs);
        }else{
            abort(403);
        }

        session()->flash('success_roles', 'Роли и права успешно обновлены!');
    }

    public function render()
    {
        return view('livewire.c-r-u-d.user-detail');
    }
}

