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

            // Сумма баллов по заявкам со статусом 'approved'
            $this->ratingPoints = FormEntry::where('user_id', $this->user->id)
                ->where('status', 'approved')
                ->with('form') // предполагается, что у FormEntry есть связь ->form()
                ->get()
                ->sum(function ($entry) {
                    return (int) optional($entry->form)->points ?? 0;
                });
        }
    }

    public function render()
    {
        return view('index');
    }
}
