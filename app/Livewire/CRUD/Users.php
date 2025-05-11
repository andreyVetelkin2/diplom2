<?php

namespace App\Livewire\CRUD;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public UserForm $form;
    public bool $editMode = false;
    public int $user_id;
    public int $perPage = 10;


    protected $paginationTheme = 'bootstrap'; // для совместимости с Bootstrap

    public function mount()
    {
        $this->perPage = config('view.page_elem');
    }
    public function resetFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->user_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $this->resetFields();
        session()->flash('message', 'Пользователь создан');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isEdit = true;
    }

    public function update()
    {


        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
        ]);

        $user = User::findOrFail($this->user_id);

        $this->authorize('update', $user);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->resetFields();
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
            'users' => User::paginate($this->perPage)
        ]);
    }
}
