<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\FormEntry;
use Livewire\Component;

class Profile extends Component
{
    public string $username;
    public int $perPage = 10;
    public int $loaded = 10;
    public array $achivments = [];
    public int $totalAchivments = 0;

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
        $this->loaded = $this->perPage;
        $this->username = optional(auth()->user())->name ?? 'Guest';
        $this->loadAchivments();
    }

    public function loadMore()
    {
        $this->loaded += $this->perPage;
        $this->loadAchivments();
    }

    private function loadAchivments()
    {
        $entries = FormEntry::where('user_id', auth()->id())->orderByDesc('created_at')->get();

        $form_ids = $entries->pluck('form_id')->unique();
        $forms = Form::with('template')->whereIn('id', $form_ids)->get()->keyBy('id');

        $all = [];

        foreach ($entries as $entry) {
            $form_id = $entry->form_id;
            if (isset($forms[$form_id])) {
                $all[] = [
                    'id' => $entry->id,
                    'title' => $forms[$form_id]->title,
                    'date'  => $entry->created_at->format('Y-m-d'),
                    'status' => $entry->status,
                ];
                    //dump($entries->pluck('id')->unique() ?? '');

            }
        }

        $this->totalAchivments = count($all);
        $this->achivments = array_slice($all, 0, $this->loaded);
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.profile');
    }
}
