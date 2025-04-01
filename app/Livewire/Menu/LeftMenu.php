<?php

namespace App\Livewire\Menu;

use Livewire\Component;

class LeftMenu extends Component
{

    public $arMenu;

    public function mount()
    {
        $this->arMenu = config('menu.index');
    }

    public function render()
    {
        return view('livewire.menu.left-menu');
    }
}
