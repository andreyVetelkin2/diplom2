<?php

namespace App\Livewire;

use Livewire\Component;

class AchievementForm extends Component
{
    public $text = 'Тут будет форма';
    public function render()
    {
        return view('livewire.achievement-form');
    }
}
