<?php

namespace App\Livewire;

use Livewire\Component;


class DateFilter extends Component
{
    public $startDate;
    public $endDate;
    public $uniqueId;

    public function mount()
    {
        $this->uniqueId = uniqid();
        $this->endDate = now()->format('Y-m-d');
    }

    public function applyFilters()
    {
        $this->validate([
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate'
        ]);

        $this->dispatch('filtersApplied',
            startDate: $this->startDate,
            endDate: $this->endDate
        );
    }

    public function setQuickDate($days)
    {
        $this->startDate = now()->subDays($days)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->applyFilters(); // Автоматически применяем фильтры при быстром выборе
    }

    public function render()
    {
        return view('livewire.date-filter');
    }
}
