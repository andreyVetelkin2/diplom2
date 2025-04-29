<?php

namespace App\Livewire\CRUD;

use App\Interfaces\Crudable;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component implements Crudable
{
    use WithPagination;

    public UserForm $form;

    public bool $editMode = false;
    public int $perPage = 10;
    public int $user_id;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
    }

    public function store()
    {

        $validated = $this->form->validate();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        $this->resetFields();
        session()->flash('message', 'Пользователь создан');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->form->setUser($user);
        $this->form->name = $user->name;
        $this->user_id = $id;
        $this->form->email = $user->email;
        $this->editMode = true;
    }

    public function resetFields(){

        $this->form->resetFields();
        $this->editMode = false;
        $this->user_id = 0;
    }

    public function update()
    {
        $validated = $this->form->validate();

        $user = User::findOrFail($this->user_id);

        $this->authorize('update', $user);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $this->resetFields();

        $this->editMode = false;
        session()->flash('message', 'Пользователь обновлён');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Пользователь удалён');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.users', [
            'users' => User::paginate($this->perPage),
        ]);
    }
}

