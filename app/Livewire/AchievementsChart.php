<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AchievementsChart extends Component
{
    public $departmentId;
    public $chartData = [];

    public function mount()
    {

        $this->departmentId = auth()->user()->department_id;
        $this->loadChartData();
    }


    protected function loadChartData()
    {
        $rows = DB::table('form_entries as fe')
            ->join('users as u', 'fe.user_id', '=', 'u.id')
            ->join('departments as d', 'u.department_id', '=', 'd.id')
            ->select(
                'u.name as user_name',
                DB::raw('YEAR(fe.created_at) as year'),
                DB::raw('COUNT(*) as achievements_count')
            )
            ->where('d.id', $this->departmentId)
            ->groupBy('u.name', DB::raw('YEAR(fe.created_at)'))
            ->orderBy('u.name')
            ->get();

        $years = $rows->pluck('year')->unique()->sort()->values()->toArray();
        $users = $rows->pluck('user_name')->unique()->sort()->values()->toArray();

        $series = [];
        foreach ($users as $user) {
            $data = [];
            foreach ($years as $year) {
                $match = $rows->first(fn($r) => $r->user_name === $user && $r->year == $year);
                $data[] = $match ? (int) $match->achievements_count : 0;
            }
            $series[] = ['name' => $user, 'data' => $data];
        }

        $this->chartData = ['series' => $series, 'years' => $years];
    }

    public function render()
    {
        return view('livewire.achievements-chart');
    }
}

