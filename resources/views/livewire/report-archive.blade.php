<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Архив отчётов</h5>
        <button wire:click="refreshReports" class="btn btn-sm btn-outline-secondary">
            Обновить
        </button>
    </div>
    <div class="card-body">

        {{-- Индивидуальные отчёты --}}
        <h6>Индивидуальные отчёты</h6>
        @if(count($individualReports) === 0)
            <p>Нет сгенерированных индивидуальных отчётов.</p>
        @else
            <div class="table-responsive mb-4">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Дата создания</th>
                        <th class="text-end">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($individualReports as $r)
                        <tr>
                            <td>{{ $r['name'] }}</td>
                            <td>{{ $r['date'] }}</td>
                            <td class="text-end">
                                <a href="{{ route('download.report', ['filename' => $r['name']]) }}"
                                   class="btn btn-sm btn-primary">
                                    Скачать
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Отчёты по кафедре --}}
        <h6>Отчёты по кафедре</h6>
        @if(count($departmentReports) === 0)
            <p>Нет сгенерированных отчётов по кафедре.</p>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                    <tr>
                        <th>Файл</th>
                        <th>Дата создания</th>
                        <th class="text-end">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($departmentReports as $r)
                        <tr>
                            <td>{{ $r['name'] }}</td>
                            <td>{{ $r['date'] }}</td>
                            <td class="text-end">
                                @can('report-on-the-departments')
                                    <a href="{{ route('download.report', ['filename' => $r['name']]) }}"
                                       class="btn btn-sm btn-primary">
                                        Скачать
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

    </div>
</div>
