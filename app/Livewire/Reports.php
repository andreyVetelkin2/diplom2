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
        // 1) Определяем пользователя или массив пользователей по вкладке
        if ($this->activeTab === 'individual') {
            $userId = auth()->id();
        } elseif ($this->activeTab === 'department' && $this->selectedDepartment) {
            $userId = \App\Models\User::where('department_id', $this->selectedDepartment)
                ->pluck('id')
                ->toArray();
        } else {
            $this->groupedData = [];
            return;
        }

        // 2) Запоминаем даты для вывода
        $this->dateFrom = $startDate;
        $this->dateTo   = $endDate;

        // 3) Загружаем категории с формами и их записями + fieldEntryValues + templateField
        $this->groupedData = Category::with(['forms' => function ($query) use ($startDate, $endDate, $userId) {
            $query
                // Только те формы, где есть хотя бы одна запись
                ->whereHas('entries', function ($q) use ($startDate, $endDate, $userId) {
                    $q->whereIn('user_id', (array) $userId)
                        ->whereBetween('date_achievement', [$startDate, $endDate])
                        ->where('status', 'approved');
                })
                // Подгружаем сами записи
                ->with(['entries' => function ($q) use ($startDate, $endDate, $userId) {
                    $q->whereIn('user_id', (array) $userId)
                        ->whereBetween('date_achievement', [$startDate, $endDate])
                        ->where('status', 'approved')
                        ->latest();
                }])
                // Подгружаем значения полей и связанные шаблонные поля
                ->with('entries.fieldEntryValues.templateField');  // ← жадная загрузка templateField
        }])
            // Фильтр категорий
            ->whereHas('forms.entries', function ($q) use ($startDate, $endDate, $userId) {
                $q->whereIn('user_id', (array) $userId)
                    ->whereBetween('date_achievement', [$startDate, $endDate])
                    ->where('status', 'approved');
            })
            ->get()
            // 4) Формируем структуру для вывода
            ->map(function ($category) {
                return [
                    'category' => $category->name,
                    'forms'    => $category->forms
                        ->map(function ($form) {
                            // Готовим записи с outputData
                            $entries = $form->entries->map(function ($entry, $idx) {
                                // Собираем пары "Название поля: значение"
                                $pairs = $entry->fieldEntryValues
                                    ->map(function ($fv) {
                                        if (
                                            $fv->templateField->type != 'file' &&
                                            $fv->templateField->type != 'checkbox'&&
                                            $fv->templateField->type != 'list'
                                        ){
                                            $label = $fv->templateField->label ?? '—';
                                            return "{$label}: {$fv->value}";
                                        }
                                    })
                                    ->toArray();
                                $pairs=array_filter($pairs);
                                // Нумерация записей (1., 2., …) и объединение через запятую
                                $line = ($idx + 1) . '. ' . implode(', ', $pairs)."\n";

                                return [
                                    'date'      => $entry->created_at->format('d.m.Y'),
                                    'outputLine'=> $line,  // ← здесь один строковый элемент
                                ];
                            })->toArray();


                            // Считаем итоговые баллы
                            $totalScore = $form->entries
                                ->reduce(function ($carry, $entry) use ($form) {
                                    $pct = $entry->percent ?? 0;
                                    return $carry + ($form->points * $pct);
                                }, 0);

                            return [
                                'name'    => $form->title,
                                'slug'    => $form->slug,
                                'points'  => $form->points,
                                'count'   => count($entries),
                                'total'   => round($totalScore, 2),
                                'entries' => $entries,
                            ];
                        })
                        ->filter(fn($form) => count($form['entries']) > 0)
                        ->values(),
                ];
            })
            ->filter(fn($category) => !empty($category['forms']))
            ->values();
    }

    public function getExportData(): array
    {
        $user = auth()->user();

        $data = [
            'position'    => $user->position ?? '',
            'full_name'   => $user->name,
            'department'  => $user->department->name ?? '',
            'date_from'   => $this->dateFrom,
            'date_to'     => $this->dateTo,
            'hirsh'       => $user->hirsh ?? '',
            'citations'   => $user->citations ?? '',
            'sections'    => [],
        ];

        foreach ($this->groupedData as $category) {
            $forms = [];

            foreach ($category['forms'] as $form) {
                // Собираем все outputLine (1. Поле:значение, …) в одну строку, разделённую переносом строки
                $entriesData = collect($form['entries'])
                    ->pluck('outputLine')               // ← берем именно outputLine
                    ->filter()
                    ->implode("\n");                   // или ', ' если нужен одинарный ряд

                $forms[] = [
                    'name'         => $form['name'],
                    'code'         => $form['slug'],
                    'points'       => $form['points'],
                    'count'        => $form['count'],      // ← теперь существует
                    'total'        => $form['total'],
                    'entries_data'=> $entriesData,         // ← новые выходные данные
                ];
            }

            if (!empty($forms)) {
                $data['sections'][$category['category']] = $forms;
            }
        }

        return $data;
    }


    public function exportIndividual()
    {
        $userid = auth()->id();
        $exporter = new ScientificReportExporter();
        $filename = $exporter->exportIndividual($this->getExportData());
        $path = storage_path("app/exports/reports/{$userid}/{$filename}");

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function exportDepartment()
    {
        $userid = auth()->id();
        $exporter = new ScientificReportExporter();
        $filename = $exporter->exportDepartment($this->getExportData());
        $path = storage_path("app/exports/reports/{$userid}/{$filename}");

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }


    public function render()
    {
        return view('livewire.reports');
    }
}

