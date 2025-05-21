<?php

namespace App\Livewire\CRUD;

use App\Interfaces\Crudable;
use App\Livewire\Forms\UserForm;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Department;

class Users extends Component implements Crudable
{
    use WithPagination;


    public UserForm $form;
    public bool $editMode = false;
    public int $user_id;
    public int $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
    }

    public function store()
    {
        $validated = $this->form->validate();
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        $this->resetFields();
        session()->flash('message', 'Пользователь создан');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->form->setUser($user);
        $this->user_id = $id;
        $this->editMode = true;
    }

    public function update()
    {
        $validated = $this->form->validate();
        $user = User::findOrFail($this->user_id);
        $user->update($validated);
        $this->resetFields();
        session()->flash('message', 'Пользователь обновлён');
    }

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Пользователь удалён');
    }

    public function resetFields()
    {
        $this->form->resetFields();
        $this->editMode = false;
        $this->user_id = 0;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.users', [
            'users' => User::with('department')->paginate($this->perPage),
            'departments' => Department::pluck('name', 'id'),
            'positions' => Position::pluck('name', 'id'),
        ]);
    }
}


