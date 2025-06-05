<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\FormEntry;
use Livewire\Component;

class ManagerCabinet extends Component
{
    public string $username;
    public int $perPage;
    public int $loaded;
    public array $achivments = [];
    public int $totalAchivments = 0;
    public ?int $limitBallovNaKvartal = null;
    public ?string $statusMessage = null;

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
        $this->loaded = $this->perPage;
        $this->username = auth()->user()->name ?? 'Guest';

        $this->loadAchivments();
    }

    public function loadMore()
    {
        $this->loaded += $this->perPage;
        $this->loadAchivments();
    }

    private function loadAchivments()
    {
        $user = auth()->user();

        $entries = FormEntry::where('status', 'review')
            ->whereHas('user', function ($q) use ($user) {
                $q->when($user->department_id, function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            })
            ->orderByDesc('created_at')
            ->get();

        $forms = Form::whereIn('id', $entries->pluck('form_id')->unique())->get()->keyBy('id');

        $all = [];
        foreach ($entries as $entry) {
            $all[] = [
                'id' => $entry->id,
                'title' => $forms[$entry->form_id]->title ?? '',
                'date' => $entry->created_at->format('Y-m-d'),
                'status' => $entry->status,
            ];
        }

        $this->totalAchivments = count($all);
        $this->achivments = array_slice($all, 0, $this->loaded);
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.manager-cabinet');
    }
}
