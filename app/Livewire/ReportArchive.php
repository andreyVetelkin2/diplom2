<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportArchive extends Component
{
    public array $reportTypes = [];

    protected array $supportedTypes = [
        'individual',
        'position',
        'forms',
        'department',
        'user',
    ];

    public function mount()
    {
        $this->loadReports();
    }

    public function loadReports(): void
    {
        $userId = auth()->id();
        $disk = Storage::disk('local');
        $prefix = "exports/reports/{$userId}";

        // Инициализация всех типов
        foreach ($this->supportedTypes as $type) {
            $this->reportTypes[$type] = [];
        }

        if (! $disk->exists($prefix)) {
            return;
        }

        $files = $disk->files($prefix);

        foreach ($files as $path) {
            $filename = basename($path);
            $timestamp = $disk->lastModified($path);
            $date = Carbon::createFromTimestamp($timestamp)->format('Y-m-d H:i:s');

            foreach ($this->supportedTypes as $type) {
                if (str_contains($filename, $type)) {
                    $this->reportTypes[$type][] = [
                        'name' => $filename,
                        'date' => $date,
                    ];
                    break;
                }
            }
        }

        // Сортировка по дате для каждого типа
        foreach ($this->reportTypes as &$reports) {
            usort($reports, fn($a, $b) => $a['date'] <=> $b['date']);
            $reports = array_reverse($reports);
        }
    }

    public function refreshReports()
    {
        $this->loadReports();
    }

    #[On('deleteConfirmed')]
    public function deleteReport(string $filename): void
    {
        $userId = auth()->id();
        $path = "exports/reports/{$userId}/{$filename}";
        $disk = Storage::disk('local');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }

        $this->loadReports();
        session()->flash('success', "Отчёт «{$filename}» успешно удалён.");
    }

    public function render()
    {
        return view('livewire.report-archive');
    }
}
