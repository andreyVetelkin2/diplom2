<?php

namespace App\Livewire\CRUD;

use App\Interfaces\Crudable;
use App\Livewire\Forms\PermissionForm;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

//TODO Добавить проверку прав на просмотр всех прав
class Permissions extends Component implements Crudable
{

    use WithPagination;

    public PermissionForm $form;

    public $permission_id;
    public $editMode = false;
    public $perPage = 0;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->perPage = config('view.page_elem');
    }

    public function resetFields()
    {
        $this->form->resetFields();
        $this->permission_id = null;
        $this->editMode = false;
    }

    public function store()
    {
        $validated = $this->form->validate();

        $this->authorize('create', Permission::class);

        Permission::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
        ]);

        $this->resetFields();
        session()->flash('message', 'Право создано');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->form->setPermission($permission);
        $this->permission_id = $permission->id;
        $this->form->name = $permission->name;
        $this->form->slug = $permission->slug;
        $this->editMode = true;
    }

    public function update()
    {
        $validated = $this->form->validate();

        $permission = Permission::findOrFail($this->permission_id);
        $this->form->setPermission($permission);

        $this->authorize('update', Permission::class);
        $permission->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
        ]);

        $this->resetFields();
        session()->flash('message', 'Право обновлено');
    }

    public function delete($id)
    {
        $this->authorize('delete', Permission::class);
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
