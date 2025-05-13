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

    public function setQuickDate($type)
    {
        $now = now();
        $year = $now->year;

        switch ($type) {
            case 'week':
                // Понедельник - Воскресенье
                $this->startDate = $now->startOfWeek()->format('Y-m-d');
                $this->endDate = $now->endOfWeek()->format('Y-m-d');
                break;

            case 'month':
                $this->startDate = $now->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->endOfMonth()->format('Y-m-d');
                break;

            case 'year':
                $this->startDate = $now->startOfYear()->format('Y-m-d');
                $this->endDate = $now->endOfYear()->format('Y-m-d');
                break;
        }

        // Можно раскомментировать для автоприменения
        // $this->applyFilters();
    }

    public function setQuarter($quarter)
    {
        $year = now()->year;

        switch ($quarter) {
            case 1:
                $start = now()->setDate($year, 1, 1);
                $end = now()->setDate($year, 3, 31);
                break;
            case 2:
                $start = now()->setDate($year, 4, 1);
                $end = now()->setDate($year, 6, 30);
                break;
            case 3:
                $start = now()->setDate($year, 7, 1);
                $end = now()->setDate($year, 9, 30);
                break;
            case 4:
                $start = now()->setDate($year, 10, 1);
                $end = now()->setDate($year, 12, 31);
                break;
            default:
                return;
        }

        $this->startDate = $start->format('Y-m-d');
        $this->endDate = $end->format('Y-m-d');
    }


    public function render()
    {
        return view('livewire.date-filter');
    }
}
