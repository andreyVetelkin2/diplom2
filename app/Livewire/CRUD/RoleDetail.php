<?php

namespace App\Livewire\CRUD;

use App\Models\Permission;
use App\Models\Role;
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
        $this->role->syncPermissions($this->selectedPermissions);
        session()->flash('success', 'Права обновлены.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.role-detail');
    }
}
