<?php

namespace App\Livewire\CRUD;

use App\Models\Permission;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Permissions extends Component
{

    use WithPagination;

    public $name, $slug, $permission_id;
    public $isEdit = false;
    public $perPage = 5;

    protected $paginationTheme = 'bootstrap'; // для совместимости с Bootstrap

    public function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->permission_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:permissions,slug',
        ]);

        Permission::create([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->resetFields();
        session()->flash('message', 'Право создано');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permission_id = $permission->id;
        $this->name = $permission->name;
        $this->slug = $permission->slug;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:permissions,slug',
        ]);

        $permission = Permission::findOrFail($this->permission_id);

        $this->authorize('update', $permission);
        $permission->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->resetFields();
        session()->flash('message', 'Право обновлено');
    }

    public function delete($id)
    {
        Permission::findOrFail($id)->delete();
        session()->flash('message', 'Право удалено');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.permissions', [
            'permissions' => Permission::paginate($this->perPage)
        ]);
    }

}
