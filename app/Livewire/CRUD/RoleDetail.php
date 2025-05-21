<?php

namespace App\Livewire\CRUD;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Component;

class RoleDetail extends Component
{

    public Role $role;

    public array $selectedPermissions = [''];
    public $allPermissions;


    public function mount(){
        $this->allPermissions = Permission::all();
        $this->selectedPermissions = $this->role
            ->permissions()
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function submit()
    {
        $permission = Permission::where('slug', 'assign-permissions-to-roles')->first();
        if (auth()->user()->hasPermissionTo($permission) || auth()->user()->hasRole('admin')) {
            $this->role->syncPermissions($this->selectedPermissions);
            session()->flash('success', 'Права обновлены.');
        }else{
            abort(403);
        }

    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.role-detail');
    }
}
