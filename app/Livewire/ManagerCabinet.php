<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\FormEntry;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ManagerCabinet extends Component
{
    public string $username;
    public int $perPage;
    public int $loaded;
    public array $achivments = [];
    public int $totalAchivments = 0;

    // Новые свойства для графика
    public array $chartCategories = []; // метки оси X — месяцы
    public array $chartSeries = [];     // данные серии

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
        $this->loaded  = $this->perPage;
        $this->username = optional(auth()->user())->name ?? 'Guest';

        $this->loadAchivments();
        $this->loadChartData();
    }

    public function loadMore()
    {
        $this->loaded += $this->perPage;
        $this->loadAchivments();
        // график можно не обновлять при пагинации
    }

    private function loadAchivments()
    {
        $user = auth()->user();

        $entries = FormEntry::where('status', 'review')
            ->whereHas('user', fn($q) => $q->where('department_id', $user->department_id))
            ->orderByDesc('created_at')
            ->get();

        $formIds = $entries->pluck('form_id')->unique();
        $forms   = Form::with('template')
            ->whereIn('id', $formIds)
            ->get()
            ->keyBy('id');

        $all = [];
        foreach ($entries as $entry) {
            if (isset($forms[$entry->form_id])) {
                $all[] = [
                    'id'     => $entry->id,
                    'title'  => $forms[$entry->form_id]->title,
                    'date'   => $entry->created_at->format('Y-m-d'),
                    'status' => $entry->status,
                ];
            }
        }

        $this->totalAchivments = count($all);
        $this->achivments      = array_slice($all, 0, $this->loaded);
    }

    /**
     * Формирует данные для графика достижений за последние 6 месяцев.
     */
    private function loadChartData(): void
    {
        $user = auth()->user();

        // Получаем записи за последние 6 месяцев
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $entries = FormEntry::where('status', 'approved')
            ->whereHas('user', fn($q) => $q->where('department_id', $user->department_id))
            ->where('created_at', '>=', $sixMonthsAgo)
            ->get(['created_at']);

        // Группируем по месяцу
        $grouped = $entries
            ->groupBy(fn($e) => $e->created_at->format('Y-m'));

        // Генерируем список последних 6 месяцев
        $months = collect();
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths(5 - $i);
            $months->push($month);
        }

        // Формируем ось X и значения
        $categories = [];
        $data = [];
        /** @var Carbon $month */
        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $categories[] = $month->format('Y-m-01'); // для ApexCharts в формате ISO
            $data[] = $grouped->has($key)
                ? $grouped->get($key)->count()
                : 0;
        }

        $this->chartCategories = $categories;
        $this->chartSeries     = [
            [
                'name' => 'Достижения',
                'data' => $data,
            ]
        ];
    }

    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('livewire.manager-cabinet');
    }
}
