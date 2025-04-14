<?php

namespace App\Livewire\Menu;

use Livewire\Component;

class LeftMenu extends Component
{

    public $arMenu;



    public function mount()
    {
        if (auth()->user()->hasRole('admin')){
            $this->arMenu = array_merge(config('menu.index'), config('menu.admin.index'));
        }else{
            $this->arMenu = config('menu.index');
        }
    }

    public function render()
    {
        return view('livewire.menu.left-menu');
    }
}
