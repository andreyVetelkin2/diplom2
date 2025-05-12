<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class ReportArchive extends Component
{
    public array $individualReports = [];
    public array $departmentReports = [];

    public function mount()
    {
        $this->loadReports();
    }

    public function loadReports(): void
    {
        $userId = auth()->id();
        $disk = Storage::disk('local');
        $prefix = "exports/reports/{$userId}";

        if (! $disk->exists($prefix)) {
            $this->individualReports = [];
            $this->departmentReports = [];
            return;
        }

        $files = $disk->files($prefix);

        $individual = [];
        $department = [];

        foreach ($files as $path) {
            $filename = basename($path);
            $timestamp = $disk->lastModified($path);
            $date = Carbon::createFromTimestamp($timestamp)->format('Y-m-d H:i:s');

            if (str_contains($filename, 'individual')) {
                $individual[] = [
                    'name' => $filename,
                    'date' => $date,
                ];
            } elseif (str_contains($filename, 'department')) {
                $department[] = [
                    'name' => $filename,
                    'date' => $date,
                ];
            }
        }

        // Сортируем по дате, новые сверху
        $sortDesc = fn($a, $b) => $a['date'] <=> $b['date'];
        usort($individual, $sortDesc); $individual = array_reverse($individual);
        usort($department, $sortDesc); $department = array_reverse($department);

        $this->individualReports = $individual;
        $this->departmentReports = $department;
    }

    public function refreshReports()
    {
        $this->loadReports();
    }

    public function render()
    {
        return view('livewire.report-archive');
    }
}
