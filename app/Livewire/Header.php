<?php

namespace App\Livewire;
use App\Livewire\Actions\Logout;
use Livewire\Component;

class Header extends Component
{

    public $links;
    public $username;
    public function mount($links = [])
    {
        $this->links = $links;
        $this->username = optional(auth()->user())->name ?? 'Guest';

    }

    public function render()
    {
        return view('livewire.header');
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}
