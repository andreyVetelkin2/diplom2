<?php

namespace App\Livewire;

use App\Livewire\Forms\FormData;
use App\Models\Form;
use Livewire\Component;
use App\Models\User;
use App\Models\FormEntry;

class Dashboard extends Component
{
    public $user;
    public $publicationCount = 0;
    public $ratingPoints = 0;
    public $pendingCount = 0;
    public $rejectedCount = 0;

    public function mount()
    {
        $this->user = auth()->user();

        if ($this->user) {
            // Кол-во поданных заявок пользователем
            $this->publicationCount = FormEntry::where('user_id', $this->user->id)->count();

            // Кол-во "на рассмотрении"
            $this->pendingCount = FormEntry::where('user_id', $this->user->id)->where('status', 'review')->count();

            // Кол-во "отклонённых"
            $this->rejectedCount = FormEntry::where('user_id', $this->user->id)->where('status', 'rejected')->count();

            // Определяем текущий квартал
            $now = now();
            $currentMonth = $now->month;
            $quarterStartMonth = ((int)(($currentMonth - 1) / 3)) * 3 + 1;

            $quarterStart = now()->startOfYear()->addMonths($quarterStartMonth - 1)->startOfMonth();
            $quarterEnd = (clone $quarterStart)->addMonths(3)->subSecond();

            // Сумма баллов по заявкам со статусом 'approved'
            // Фильтрация по статусу и дате текущего квартала
            $this->user = auth()->user();
            $this->ratingPoints = FormEntry::where('user_id', $this->user->id)
                ->where('status', 'approved')
                ->whereBetween('date_achievement', [$quarterStart, $quarterEnd])
                ->with('form')
                ->get()
                ->sum(function ($entry) {
                    return (int) (optional($entry->form)->points*$entry->percent) ?? 0;
                });
        }
    }

    public function render()
    {
        return view('index');
    }
}
