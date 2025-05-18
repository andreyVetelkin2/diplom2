<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class BulkLimitSetter extends Component
{
    public $limit_ballov_na_kvartal;

    public function mount()
    {
        // по умолчанию можно взять текущий глобальный лимит (если есть настройка)
        // или 0
        $this->limit_ballov_na_kvartal = auth()->user()->limit_ballov_na_kvartal;

    }

    public function updateAll()
    {
        $this->validate([
            'limit_ballov_na_kvartal' => 'required|integer|min:0',
        ]);

        User::query()->update([
            'limit_ballov_na_kvartal' => $this->limit_ballov_na_kvartal
        ]);

        session()->flash('message', 'Максимальное количество баллов обновлено');

    }
    public function render()
    {
        return view('livewire.bulk-limit-setter');
    }
}
