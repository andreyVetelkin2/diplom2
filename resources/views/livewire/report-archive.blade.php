<div class="card shadow-sm border-0">
    <div class="card-header bg-primary d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-light">📁 Архив отчётов</h5>
        <button wire:click="refreshReports" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
            <i class="bi bi-arrow-clockwise"></i> Обновить
        </button>
    </div>

    <div class="card-body">
        @php
            $titles = [
                'individual' => '👤 Индивидуальные отчёты',
                'department' => '🏫 Отчёты по кафедре',
                'position'   => '💼 Отчёты по должностям',
                'forms'      => '📊 Отчёты по показателям',
                'user'      => '👨‍👨‍ Отчёты по пользователям',
            ];

            $permissions = [
                'individual' => null,
                'department' => 'archive-report-on-the-departments',
                'position'   => 'archive-report-on-positions',
                'forms'      => 'archive-report-on-forms',
                'user'      => 'archive-report-on-users',
            ];
        @endphp

        @foreach($reportTypes as $type => $reports)
            @php
                $title = $titles[$type] ?? ucfirst($type);
                $permission = $permissions[$type] ?? null;
            @endphp

            @if(is_null($permission) || auth()->user()->can($permission))
                <h6 class="fw-bold text-secondary mb-3">{{ $title }}</h6>

                @if(count($reports) === 0)
                    <div class="alert alert-info py-2 px-3 small">Нет сгенерированных отчётов по данному типу.</div>
                @else
                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-middle table-bordered small">
                            <thead class="table-light">
                            <tr>
                                <th>📄 Файл</th>
                                <th>📅 Дата создания</th>
                                <th class="text-end">🔽 Скачать</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $r)
                                <tr>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $r['name'] }}</td>
                                    <td>{{ $r['date'] }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('download.report', ['filename' => $r['name']]) }}"
                                           class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                                            <i class="bi bi-download"></i> Скачать
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</div>
