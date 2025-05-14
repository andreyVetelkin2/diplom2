<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

//TODO: Пока не работает, проблемы с js  не перерисовывает график
class MultiChartManager extends Component
{
    public string $selectedChart = 'publications_per_year';
    public array  $chartOptions   = [];
    public array  $chartSeries    = [];

    public function mount(): void
    {
        $this->updateChart($this->selectedChart);
    }

    // будет автоматически вызван Livewire, когда $selectedChart изменится
    public function updatedSelectedChart(string $value): void
    {
        $this->updateChart($value);
    }
    public function updateChart(string $chartKey): void
    {
        $this->selectedChart = $chartKey;

        switch ($chartKey) {
            case 'publications_per_year':
                $data = DB::table('form_entries')
                    ->selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                $this->chartSeries = [['name' => 'Публикации', 'data' => $data->pluck('count')->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'line', 'height' => 350],
                    'xaxis' => ['categories' => $data->pluck('year')->toArray()],
                    'title' => ['text' => 'Публикации по годам']
                ];
                break;

            case 'citations_per_year':
                $data = DB::table('authors')
                    ->selectRaw('YEAR(created_at) as year, SUM(cited_by) as citations')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();
                $this->chartSeries = [['name' => 'Цитирования', 'data' => $data->pluck('citations')->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'area', 'height' => 350],
                    'xaxis' => ['categories' => $data->pluck('year')->toArray()],
                    'title' => ['text' => 'Цитирования по годам']
                ];
                break;

            case 'department_rating':
                $data = DB::table('users')
                    ->join('departments', 'users.department_id', '=', 'departments.id')
                    ->selectRaw('departments.name as dept, AVG(users.rating) as avg_rating')
                    ->groupBy('dept')
                    ->orderBy('avg_rating', 'desc')
                    ->get();
                $this->chartSeries = [['name' => 'Средний рейтинг', 'data' => $data->pluck('avg_rating')->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'bar', 'height' => 350],
                    'xaxis' => ['categories' => $data->pluck('dept')->toArray()],
                    'title' => ['text' => 'Средний рейтинг по департаментам']
                ];
                break;

            case 'status_distribution':
                $data = DB::table('form_entries')
                    ->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get();
                $this->chartSeries = $data->pluck('count')->toArray();
                $this->chartOptions = [
                    'chart' => ['type' => 'pie', 'height' => 350],
                    'labels' => $data->pluck('status')->toArray(),
                    'title' => ['text' => 'Распределение статусов записей']
                ];
                break;

            case 'top_authors':
                $data = DB::table('authors')
                    ->orderBy('cited_by', 'desc')
                    ->limit(10)
                    ->get();
                $this->chartSeries = [['name' => 'Цитирования', 'data' => $data->pluck('cited_by')->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'bar', 'height' => 350],
                    'xaxis' => ['categories' => $data->pluck('name')->toArray()],
                    'title' => ['text' => 'Топ-10 авторов по цитированиям']
                ];
                break;

            case 'average_percent':
                $data = DB::table('form_entries')
                    ->selectRaw('form_template_id, AVG(percent) as avg_percent')
                    ->groupBy('form_template_id')
                    ->get();
                $templates = DB::table('form_templates')
                    ->whereIn('id', $data->pluck('form_template_id'))
                    ->pluck('name', 'id');
                $this->chartSeries = [['name' => 'Средний % участия', 'data' => $data->pluck('avg_percent')->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'column', 'height' => 350],
                    'xaxis' => ['categories' => $data->pluck('form_template_id')->map(fn($id) => $templates[$id])->toArray()],
                    'title' => ['text' => 'Средний % по шаблонам']
                ];
                break;

            case 'scatter_hirsh_citations':
                $authors = DB::table('users')->select('name', 'hirsh', 'citations')->get();
                $this->chartSeries = [['name' => 'H-индекс vs Цитирования', 'data' => $authors->map(fn($a) => [$a->hirsh, $a->citations])->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'scatter', 'height' => 350],
                    'xaxis' => ['title' => ['text' => 'H-индекс']],
                    'yaxis' => ['title' => ['text' => 'Цитирования']],
                    'title' => ['text' => 'H-индекс vs Цитирования']
                ];
                break;

            case 'interests_radar':
                $interests = DB::table('authors')
                    ->pluck('interests')
                    ->flatMap(fn($s) => explode(',', $s))
                    ->countBy()
                    ->sortDesc();
                $this->chartSeries = [['name' => 'Интересы', 'data' => $interests->values()->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'radar', 'height' => 350],
                    'labels' => $interests->keys()->toArray(),
                    'title' => ['text' => 'Популярные интересы']
                ];
                break;

            case 'donut_categories':
                $data = DB::table('forms')
                    ->join('categories', 'forms.category_id', '=', 'categories.id')
                    ->selectRaw('categories.name, COUNT(forms.id) as count')
                    ->groupBy('categories.name')
                    ->get();
                $this->chartSeries = $data->pluck('count')->toArray();
                $this->chartOptions = [
                    'chart' => ['type' => 'donut', 'height' => 350],
                    'labels' => $data->pluck('name')->toArray(),
                    'title' => ['text' => 'Донат по категориям форм']
                ];
                break;

            case 'penalty_points_chart':
                $data = DB::table('penalty_points')
                    ->join('users', 'penalty_points.user_id', '=', 'users.id')
                    ->select('users.name as name', 'penalty_points.penalty_points')
                    ->orderBy('penalty_points.penalty_points', 'desc')
                    ->get();
                $this->chartSeries = [['name' => 'Штрафные баллы', 'data' => $data->pluck('penalty_points')->toArray()]];
                $this->chartOptions = [
                    'chart' => ['type' => 'bar', 'height' => 350],
                    'xaxis' => ['categories' => $data->pluck('name')->toArray()],
                    'title' => ['text' => 'Штрафные баллы сотрудников']
                ];
                break;
        }

    }

    public function render()
    {
        return view('livewire.multi-chart-manager');
    }
}
