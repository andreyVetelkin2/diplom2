<div class="card shadow-sm border-0">
    <div class="card-header bg-primary d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-light">📁 Архив отчётов</h5>
        <button wire:click="refreshReports" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1">
            <i class="bi bi-arrow-clockwise"></i> Обновить
        </button>
    </div>

    <div class="card-body">
        {{-- Индивидуальные отчёты --}}
        <h6 class="fw-bold text-secondary mb-3">👤 Индивидуальные отчёты</h6>

        @if(count($individualReports) === 0)
            <div class="alert alert-info py-2 px-3 small">Нет сгенерированных индивидуальных отчётов.</div>
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
                    @foreach($individualReports as $r)
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

        @can('archive-report-on-the-departments')
            {{-- Отчёты по кафедре --}}
            <h6 class="fw-bold text-secondary mb-3">🏫 Отчёты по кафедре</h6>

            @if(count($departmentReports) === 0)
                <div class="alert alert-info py-2 px-3 small">Нет сгенерированных отчётов по кафедре.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered small">
                        <thead class="table-light">
                        <tr>
                            <th>📄 Файл</th>
                            <th>📅 Дата создания</th>
                            <th class="text-end"><i class="bi bi-download"></i> Скачать</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($departmentReports as $r)
                            <tr>
                                <td class="text-truncate" style="max-width: 200px;">{{ $r['name'] }}</td>
                                <td>{{ $r['date'] }}</td>
                                <td class="text-end">
                                    @can('report-on-the-departments')
                                        <a href="{{ route('download.report', ['filename' => $r['name']]) }}"
                                           class="btn btn-sm btn-outline-success d-flex justify-content-center align-items-center gap-1">
                                            <div class="">
                                                Скачать
                                            </div>
                                        </a>
                                    @else
                                        <span class="text-muted">Недоступно</span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endcan
    </div>
</div>
