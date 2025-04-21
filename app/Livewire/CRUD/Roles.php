<?php

namespace App\Livewire\CRUD;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Roles extends Component
{

    use WithPagination;

    public $name, $slug, $role_id;
    public $isEdit = false;
    public $perPage = 0;

    protected $paginationTheme = 'bootstrap'; // для совместимости с Bootstrap

    public function mount()
    {
        $this->perPage = config('view.page_elem');

    }

    public function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->role_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles,slug',
        ]);

        $this->authorize('create', Role::class);

        Role::create([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->resetFields();
        session()->flash('message', 'Роль создана');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $role->id;
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $this->role_id,
        ]);

        $role = Role::findOrFail($this->role_id);

        $this->authorize('update', Role::class);

        $role->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->resetFields();
        session()->flash('message', 'Роль обновлена');
    }

    public function delete($id)
    {
        $this->authorize('delete', Role::class);
        Role::findOrFail($id)->delete();
        session()->flash('message', 'Роль удалена');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.roles', [
            'roles' => Role::paginate($this->perPage)
        ]);
    }

}
