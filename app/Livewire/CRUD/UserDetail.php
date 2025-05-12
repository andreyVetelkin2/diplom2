<?php
namespace App\Livewire\CRUD;

use App\Models\Department;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserDetail extends Component
{
    public User $user;
    public array $user_field = [];

    public string $password = '';
    public string $password_confirmation = '';

    public array $selectedRoles = [];
    public array $selectedPermissions = [];

    public $allRoles;
    public $allPermissions;
    protected UserService $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;

        $this->user_field = $this->user->toArray();

    }
    public function updateUserInfo()
    {
        $this->validate([
            'user_field.name' => 'required|string|max:255',
            'user_field.email' => 'required|email|unique:users,email,' . $this->user->id,
            'user_field.position' => 'nullable|string|max:255',
            'user_field.department_id' => 'nullable|exists:departments,id',
        ]);

        // Преобразуем пустую строку в null
        foreach (['position', 'department_id'] as $field) {
            if (isset($this->user_field[$field]) && $this->user_field[$field] === '') {
                $this->user_field[$field] = null;
            }
        }

        $this->user->update($this->user_field);

        session()->flash('success_info', 'Информация успешно обновлена!');
    }


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

        $this->userService->updatePassword($this->user, $this->password);

        session()->flash('success', 'Пароль успешно обновлён!');
        $this->reset(['password', 'password_confirmation']);
    }


    public function updateRolesAndPermissions()
    {
        $permission = Permission::where('slug', 'assign-roles-to-users')->first();
        if (!auth()->user()->hasPermissionTo($permission)) {
            abort(403);
        }

        $this->userService->syncRoles($this->user, $this->selectedRoles);
        $this->userService->syncPermissions($this->user, $this->selectedPermissions);

        session()->flash('success_roles', 'Роли и права успешно обновлены!');
    }


    public function render()
    {
        return view('livewire.c-r-u-d.user-detail',[
            'departments' => Department::pluck('name', 'id'),
        ]);
    }
}
