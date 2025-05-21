<?php

namespace App\Livewire\CRUD;

use App\Interfaces\Crudable;
use App\Livewire\Forms\RoleForm;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportEvents\BaseOn;
use Livewire\WithPagination;

//TODO Добавить проверку прав на просмотр всех ролей

class Roles extends Component implements Crudable
{

    use WithPagination;

    public RoleForm $form;

    public  $role_id;
    public $editMode = false;
    public $perPage = 0;

    protected $paginationTheme = 'bootstrap'; // для совместимости с Bootstrap

    public function mount()
    {
        $this->perPage = config('view.page_elem');

    }

    public function resetFields()
    {
        $this->form->resetFields();
        $this->editMode = false;
        $this->role_id = 0;

    }

    public function store()
    {
        $validated = $this->form->validate();

        $this->authorize('create', Role::class);

        Role::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
        ]);

        $this->resetFields();
        session()->flash('message', 'Роль создана');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->form->setRole($role);
        $this->role_id = $role->id;
        $this->form->name = $role->name;
        $this->form->slug = $role->slug;
        $this->editMode = true;
    }

    public function update()
    {
        $validated = $this->form->validate();

        $role = Role::findOrFail($this->role_id);

        $this->authorize('update', Role::class);

        $role->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
        ]);

        $this->resetFields();
        session()->flash('message', 'Роль обновлена');
    }

    #[On('deleteConfirmed')]
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
