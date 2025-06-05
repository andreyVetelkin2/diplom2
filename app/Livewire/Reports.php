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
        if (auth()->user()->can('report-on-the-departments')){
            $this->users = User::pluck('name', 'id')->toArray();

        }else{
            $dep = auth()->user()->department->id;
            $this->users = User::where('department_id', $dep)->pluck('name', 'id')->toArray();
        }


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
        $user = auth()->user();
        $onlyOwnDepartment = !$user->can('report-on-the-departments');
        $ownDepartmentId = $user->department_id;

        $isFormsTab = $this->activeTab === 'forms' && !empty($this->selectedForms);

        if (!$isFormsTab) {
            switch ($this->activeTab) {
                case 'individual':
                    $userIds = [$user->id];
                    break;

                case 'department':
                    $departments = $onlyOwnDepartment
                        ? array_intersect($this->selectedDepartment ?? [], [$ownDepartmentId])
                        : ($this->selectedDepartment ?? []);

                    if (empty($departments)) {
                        $this->groupedData = [];
                        return;
                    }

                    $userIds = User::whereIn('department_id', $departments)->pluck('id')->toArray();
                    break;

                case 'user':
                    $userIds = (array) $this->selectedUser;
                    if ($onlyOwnDepartment) {
                        $userIds = User::whereIn('id', $userIds)
                            ->where('department_id', $ownDepartmentId)
                            ->pluck('id')
                            ->toArray();
                    }
                    break;

                case 'position':
                    $userQuery = User::whereIn('position_id', $this->selectedPositions ?? []);
                    if ($onlyOwnDepartment) {
                        $userQuery->where('department_id', $ownDepartmentId);
                    }
                    $userIds = $userQuery->pluck('id')->toArray();
                    break;

                default:
                    $this->groupedData = [];
                    return;
            }
        } else {
            // Вкладка "forms"
            $formIds = $this->selectedForms;

            $userQuery = User::query();
            if ($onlyOwnDepartment) {
                $userQuery->where('department_id', $ownDepartmentId);
            }
            $userIds = $userQuery->pluck('id')->toArray();
        }

        $this->dateFrom = $startDate;
        $this->dateTo = $endDate;

        $query = FormEntry::with([
            'fieldEntryValues.templateField',
            'form:id,title,slug,points,category_id',
            'form.category:id,name',
        ])
            ->whereBetween('date_achievement', [$startDate, $endDate])
            ->where('status', 'approved')
            ->whereIn('user_id', $userIds);

        if ($isFormsTab) {
            $query->whereIn('form_id', $formIds);
        }

        $entries = $query
            ->orderBy('user_id')
            ->orderBy('form_id')
            ->orderBy('created_at')
            ->get();

        $penaltyPoints = \DB::table('penalty_points')
            ->select('id', 'user_id', 'penalty_points', 'date', 'comment')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereIn('user_id', $userIds)
            ->get();

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

                                $totalScore = $entriesByForm->sum('points');

                                return [
                                    'name' => $formModel->title,
                                    'slug' => $formModel->slug,
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
            'report_type' => $this->activeTab,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'blocks' => array_map(function ($block) {
                // Преобразуем sections в массив, если это коллекция
                $sections = $block['sections'] instanceof \Illuminate\Support\Collection
                    ? $block['sections']->toArray()
                    : (array) $block['sections'];

                return [
                    'full_name' => $block['user'],
                    'position' => User::where('name', $block['user'])->first()?->position->name ?? '',
                    'department' => User::where('name', $block['user'])->first()?->department->name ?? '',
                    'hirsh' => User::where('name', $block['user'])->first()?->hirsh ?? '',
                    'citations' => User::where('name', $block['user'])->first()?->citations ?? '',
                    'sections' => array_map(function ($s) {
                        // Преобразуем forms в массив, если это коллекция
                        $forms = $s['forms'] instanceof \Illuminate\Support\Collection
                            ? $s['forms']->toArray()
                            : (array) $s['forms'];

                        return [
                            'category' => $s['category'],
                            'forms' => array_map(fn($f) => [
                                'name' => $f['name'],
                                'code' => $f['slug'],
                                'count' => $f['count'],
                                'total' => $f['total'],
                                'entries_data' => collect($f['entries'])->pluck('outputLine')->implode("\n"),
                            ], $forms),
                        ];
                    }, $sections),
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

