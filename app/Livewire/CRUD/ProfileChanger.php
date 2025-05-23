<?php

namespace App\Livewire\CRUD;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ProfileChanger extends Component
{
    public User $user;

    public string $username = '';
    public string $useremail = '';
    public string $userpos = '';

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
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->allRoles = Role::all();
        $this->allPermissions = Permission::all();

        $this->selectedRoles = $this->user->roles->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selectedPermissions = $this->user->permissions->pluck('id')->map(fn($id) => (string)$id)->toArray();
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

    public function updateProfile()
    {
        // Валидация данных
        $this->validate([
            'user.name' => 'string|max:255',
            'user.email' => 'email|max:255|unique:users,email,' . $this->user->id,
        ]);

        if ($this->username)
        {
            $this->user->name = $this->username;
        }
        if ($this->useremail)
        {
            $this->user->email = $this->useremail;
        }


        // Обновление данных пользователя
        $this->user->save();

        // Сообщение об успешном обновлении
        session()->flash('success', 'Данные успешно обновлены!');
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
        return view('livewire.profile-changer');
    }
}

