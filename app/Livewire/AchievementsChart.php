<?php

namespace App\Livewire;

use App\Models\FormEntry;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\Department;

class AchievementsChart extends Component
{
    public $chartData = [];
    public array $chartCategories = [];
    public array $chartSeries     = [];

    public $startDate;
    public $endDate;

    public $departments = [];
    public $selectedDepartment = null;

    public function mount()
    {
        if (auth()->user()->can('report-on-the-departments')){
            $this->departments = Department::pluck('name', 'id')->toArray();

        }else{
            $dep = auth()->user()->department->id;
            $this->departments = Department::where('id', $dep)->pluck('name', 'id')->toArray();
        }


        $this->selectedDepartment = session('achievements_department')?? auth()->user()->department?->id; // может быть null

        $this->startDate = session('achievements_start', now()->subYears(5)->startOfYear()->format('Y-m-d'));
        $this->endDate = session('achievements_end', now()->format('Y-m-d'));

        $this->loadChartData();
    }

    public function applyFilter()
    {
        session([
            'achievements_start' => $this->startDate,
            'achievements_end' => $this->endDate,
            'achievements_department' => $this->selectedDepartment,
        ]);

        return redirect()->route('manager-cabinet'); // заменить на актуальный маршрут
    }

    private function loadChartData(): void
    {
        $usersNames = User::pluck('name','id')->toArray();
        $usersDepartments = User::with('department')->get()
            ->pluck('department.name', 'id')
            ->toArray();


        $start = Carbon::parse($this->startDate)->startOfMonth();
        $end = Carbon::parse($this->endDate)->endOfMonth();

        $entries = FormEntry::where('status', 'approved')
            ->when($this->selectedDepartment, function ($query) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $this->selectedDepartment));
            })
            ->whereBetween('date_achievement', [$start, $end])
            ->get(['date_achievement', 'user_id']);

        $grouped = $entries
            ->groupBy(fn($e) => Carbon::parse($e->date_achievement)->format('Y-m'))
            ->map(fn($group) => $group->groupBy('user_id')->map->count());

        $months = collect();
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $months->push($cursor->copy());
            $cursor->addMonth();
        }

        $categories = [];
        $users = $entries->pluck('user_id')->unique();
        $series = [];

        foreach ($users as $userId) {
            $userData = [];
            foreach ($months as $month) {
                $key = $month->format('Y-m');
                $userData[] = $grouped[$key][$userId] ?? 0;
            }
            $series[] = [
                'name' => $usersNames[$userId].' '.$usersDepartments[$userId] ?? 'Неизвестный',
                'data' => $userData,
            ];
        }

        foreach ($months as $month) {
            $categories[] = $month->format('Y-m-01');
        }

        $this->chartCategories = $categories;
        $this->chartSeries = $series;
    }

    public function render()
    {
        return view('livewire.achievements-chart');
    }
}
