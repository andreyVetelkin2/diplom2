<?php

namespace App\Livewire;


use App\Models\Permission;
use App\Models\Category;
use App\Models\Department;
use App\Services\ScientificReportExporter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Reports extends Component
{
    public $groupedData = [];
    public $selectReportTypes = [];
    public $activeTab = 'individual'; // вкладка по умолчанию
    public $selectedDepartment = null;
    public $departments = [];
    public $dateFrom;
    public $dateTo;
    public $docxFilePath;
    public string $downloadLink = '';


    public function mount()
    {
        $this->user = auth()->user();

        $permissions = Permission::whereIn('slug', [
            'report-on-the-departments',
            'report-on-the-institutes',
            'report-on-the-universities'
        ])->get();

        foreach ($permissions as $permission) {
            if ($this->user->hasPermissionTo($permission)) {
                $this->selectReportTypes[] = $permission->slug;
            }
        }

        // загрузка кафедр (можно ограничить доступные кафедры по ролям)
        $this->departments = Department::pluck('name', 'id')->toArray();
    }

    protected $listeners = ['filtersApplied' => 'loadGroupedData'];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->selectedDepartment = null;
        $this->groupedData = [];
    }

    public function updatedSelectedDepartment()
    {
        // автоматическая подгрузка при смене кафедры
        $this->dispatch('requestFilters'); // запускаем компонент фильтра даты
    }

    public function loadGroupedData($startDate, $endDate)
    {


        if ($this->activeTab === 'individual') {
            $userId = auth()->id();
        } elseif ($this->activeTab === 'department' && $this->selectedDepartment) {
            // выбираем всех пользователей из выбранной кафедры
            $userId = \App\Models\User::where('department_id', $this->selectedDepartment)->pluck('id')->toArray();
        } else {
            $this->groupedData = [];
            return;
        }

        $this->dateFrom = $startDate;
        $this->dateTo = $endDate;

        $this->groupedData = Category::with(['forms' => function ($query) use ($startDate, $endDate, $userId) {
            $query->whereHas('entries', function ($q) use ($startDate, $endDate, $userId) {
                $q->whereIn('user_id', (array) $userId)
                    ->whereBetween('date_achievement', [$startDate, $endDate]);
            })
                ->withCount(['entries as user_entries_count' => function ($query) use ($startDate, $endDate, $userId) {
                    $query->whereIn('user_id', (array) $userId)
                        ->whereBetween('date_achievement', [$startDate, $endDate]);
                }])
                ->with(['entries' => function ($query) use ($startDate, $endDate, $userId) {
                    $query->whereIn('user_id', (array) $userId)
                        ->whereBetween('date_achievement', [$startDate, $endDate])
                        ->latest();
                }]);
        }])
            ->whereHas('forms.entries', function ($q) use ($startDate, $endDate, $userId) {
                $q->whereIn('user_id', (array) $userId)
                    ->whereBetween('date_achievement', [$startDate, $endDate]);
            })
            ->get()
            ->map(function ($category) {
                return [
                    'category' => $category->name,
                    'forms' => $category->forms->map(function ($form) {
                        return [
                            'name' => $form->title,
                            'slug' => $form->slug,
                            'points' => $form->points,
                            'count' => $form->user_entries_count,
                            'total' => $form->points * $form->user_entries_count,
                            'entries' => $form->entries,
                        ];
                    })->filter(fn($form) => $form['count'] > 0)
                ];
            })->filter(fn($category) => !empty($category['forms']));


    }

    public function getExportData()
    {
        $user = auth()->user();

        $data = [
            'position' => $user->position ?? '',
            'full_name' => $user->name,
            'department' => $user->department->name ?? '',
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'hirsch' => $user->hirsch_index ?? '',
            'citations' => $user->citation_count ?? '',
            'sections' => [],
        ];

        foreach ($this->groupedData as $category) {
            $forms = [];

            foreach ($category['forms'] as $form) {
                foreach ($form['entries'] as $entry) {
                    $forms[] = [
                        'name' => $form['name'],
                        'code' => $form['slug'],
                        'points' => $form['points'],
                        'justification' => $entry->justification ?? '',
                    ];
                }
            }

            if (!empty($forms)) {
                $data['sections'][$category['category']] = $forms;
            }
        }

        return $data;
    }



    public function exportIndividual()
    {
        $exporter = new ScientificReportExporter();
        $filename = $exporter->exportIndividual($this->getExportData());

        $this->downloadLink = route('download.report', ['filename' => $filename]);
        $this->redirect($this->downloadLink);
    }

    public function exportDepartment()
    {
        $exporter = new ScientificReportExporter();
        $filename = $exporter->exportDepartment($this->getExportData());

        $this->downloadLink = route('download.report', ['filename' => $filename]);
        $this->redirect($this->downloadLink);
    }


    public function render()
    {
        return view('livewire.reports');
    }
}

