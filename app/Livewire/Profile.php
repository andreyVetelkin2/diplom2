<?php

namespace App\Livewire;

use Livewire\Component;

class Profile extends Component
{
    public $username;

    public function mount()
    {
        $this->username = optional(auth()->user())->name ?? 'Guest';
    }

    public function render()
    {
        return view('profile');
    }
}
