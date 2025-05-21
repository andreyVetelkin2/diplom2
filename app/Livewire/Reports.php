<?php

namespace App\Livewire;


use App\Models\Form;
use App\Models\FormEntry;
use App\Models\Permission;
use App\Models\Category;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Services\ScientificReportExporter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Reports extends Component
{
    public $groupedData = [];


    public $activeTab = 'individual';// вкладка по умолчанию

    public $selectedDepartment = [];
    public $selectedUser = null;
    public $selectedForms = null;
    public $selectedPositions = null;

    public $departments = [];
    public $users = [];
    public $positions = [];
    public $forms = [];

    public $dateFrom;
    public $dateTo;
    public $docxFilePath;
    public string $downloadLink = '';


    public function mount()
    {
        $this->user = auth()->user();

        // загрузка кафедр (можно ограничить доступные кафедры по ролям)
        $this->departments = Department::pluck('name', 'id')->toArray();
        $this->users = User::pluck('name', 'id')->toArray();
        $this->positions = Position::pluck('name', 'id')->toArray();
        $this->forms = Form::pluck('title', 'id')->toArray();
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
        $isFormsTab = $this->activeTab === 'forms' && !empty($this->selectedForms);

        if (!$isFormsTab) {
            if ($this->activeTab === 'individual') {
                $userIds = [auth()->id()];
            } elseif ($this->activeTab === 'department' && $this->selectedDepartment) {
                $userIds = User::whereIn('department_id', $this->selectedDepartment)
                    ->pluck('id')->toArray();
            } elseif ($this->activeTab === 'user' && $this->selectedUser) {
                $userIds = (array)$this->selectedUser;
            } elseif ($this->activeTab === 'position' && $this->selectedPositions) {
                $userIds = User::whereIn('position_id', $this->selectedPositions)
                    ->pluck('id')->toArray();
            } else {
                $this->groupedData = [];
                return;
            }
        } else {
            $formIds = $this->selectedForms;
        }

        $this->dateFrom = $startDate;
        $this->dateTo = $endDate;

        // Получаем достижения
        $query = FormEntry::with([
            'fieldEntryValues.templateField',
            'form:id,title,slug,points,category_id',
            'form.category:id,name',
        ])
            ->whereBetween('date_achievement', [$startDate, $endDate])
            ->where('status', 'approved');

        if ($isFormsTab) {
            $query->whereIn('form_id', $formIds);
        } else {
            $query->whereIn('user_id', $userIds);
        }

        $entries = $query
            ->orderBy('user_id')
            ->orderBy('form_id')
            ->orderBy('created_at')
            ->get();

        // Получаем штрафные баллы
        $penaltyPoints = \DB::table('penalty_points')
            ->select('id', 'user_id', 'penalty_points', 'date', 'comment')
            ->whereBetween('date', [$startDate, $endDate]);

        if (!$isFormsTab) {
            $penaltyPoints->whereIn('user_id', $userIds);
        } else {
            $penaltyPoints = collect(); // пустая коллекция, если активна вкладка "Формы"
        }

        $penaltyPoints = $penaltyPoints->get();

        // Группировка по пользователям
        $this->groupedData = $entries
            ->groupBy('user_id')
            ->map(function ($userEntries, $uid) use ($penaltyPoints) {
                $user = User::find($uid);

                $sections = $userEntries
                    ->groupBy(fn($e) => $e->form->category->name)
                    ->map(function ($entriesByCat, $categoryName) {
                        $forms = $entriesByCat
                            ->groupBy('form_id')
                            ->map(function ($entriesByForm) {
                                $formModel = $entriesByForm->first()->form;
                                $entries = $entriesByForm->map(function ($entry, $idx) {
                                    $pairs = $entry->fieldEntryValues
                                        ->filter(fn($fv) => in_array($fv->templateField->name, ['title', 'name', 'label', 'nazvanie']))
                                        ->map(fn($fv) => "{$fv->value}")
                                        ->toArray();

                                    return [
                                        'date' => $entry->created_at->format('d.m.Y'),
                                        'outputLine' => ($idx + 1) . '. ' . implode(', ', $pairs),
                                    ];
                                })->toArray();

                                $totalScore = $entriesByForm
                                    ->reduce(fn($carry, $entry) => $carry + ($formModel->points * ($entry->percent ?? 0)), 0
                                    );

                                return [
                                    'name' => $formModel->title,
                                    'slug' => $formModel->slug,
                                    'points' => $formModel->points,
                                    'count' => count($entries),
                                    'total' => round($totalScore, 2),
                                    'entries' => $entries,
                                ];
                            })
                            ->sortBy('name')
                            ->values();

                        return [
                            'category' => $categoryName,
                            'forms' => $forms,
                        ];
                    })
                    ->values()
                    ->toArray();

                // Добавляем штрафные баллы как отдельную категорию
                $penaltiesForUser = $penaltyPoints->where('user_id', $uid);

                if ($penaltiesForUser->isNotEmpty()) {
                    $penaltyEntries = $penaltiesForUser->map(function ($p, $idx) {
                        return [
                            'date' => \Carbon\Carbon::parse($p->date)->format('d.m.Y'),
                            'outputLine' => ($idx + 1) . '. Комментарий: ' . ($p->comment ?: '—'),
                        ];
                    })->toArray();

                    $penaltyTotal = $penaltiesForUser->sum('penalty_points');

                    $sections[] = [
                        'category' => 'Штрафы',
                        'forms' => [
                            [
                                'name' => 'Штрафные баллы',
                                'slug' => 'ШБ',
                                'points' => -1,
                                'count' => count($penaltyEntries),
                                'total' => -round($penaltyTotal, 2),
                                'entries' => $penaltyEntries,
                            ]
                        ]
                    ];
                }

                return [
                    'user' => $user->name,
                    'sections' => $sections,
                ];
            })
            ->sortBy('user')
            ->values()
            ->toArray();
    }


    public function getExportData(): array
    {
        return [
            'report_type' => $this->activeTab,      // 'individual', 'department', 'user' или 'position'
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'blocks' => array_map(function ($block) {
                // в groupedData у нас уже лежит нужная информация:
                //   'user'    => имя
                //   'sections'=> [ ['category'=>..., 'forms'=>[...]], ... ]
                return [
                    'full_name' => $block['user'],
                    'position' => User::where('name', $block['user'])->first()?->position->name ?? '',
                    'department' => User::where('name', $block['user'])->first()?->department->name ?? '',
                    'hirsh' => User::where('name', $block['user'])->first()?->hirsh ?? '',
                    'citations' => User::where('name', $block['user'])->first()?->citations ?? '',
                    'sections' => array_map(fn($s) => [
                        'category' => $s['category'],
                        'forms' => array_map(fn($f) => [
                            'name' => $f['name'],
                            'code' => $f['slug'],
                            'points' => $f['points'],
                            'count' => $f['count'],
                            'total' => $f['total'],
                            'entries_data' => collect($f['entries'])->pluck('outputLine')->implode("\n"),
                        ], $s['forms']->toArray())
                    ], $block['sections']->toArray()),
                ];
            }, $this->groupedData),
        ];
    }

    public function export()
    {
        $exporter = new ScientificReportExporter();
        $data = $this->getExportData();
        // универсальный метод
        $filename = $exporter->exportReport($data['report_type'], $data);
        $path = storage_path("app/exports/reports/" . auth()->id() . "/{$filename}");

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function render()
    {
        return view('livewire.reports');
    }
}

