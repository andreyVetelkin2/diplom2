<?php
namespace App\Livewire\CRUD;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserDetail extends Component
{
    public User $user;

    #[Validate('required|min:6|confirmed')]
    public string $password = '';


    public string $password_confirmation = '';

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.user-detail');
    }

    public function updatePassword()
    {
        $this->validate();

        $this->user->password = Hash::make($this->password);
        $this->user->save();

        session()->flash('success', 'Пароль успешно обновлён!');
        $this->reset(['password', 'password_confirmation']);
    }
}

