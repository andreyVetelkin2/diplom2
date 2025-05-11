<div class="row gy-4">

    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Новые достижения сотрудников для утверждения</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Название</th>
                        <th>Дата</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($achivments as $achivement)
                        <tr>
                            <td>
                                <a href="{{ route('form-entry', $achivement['id']) }}" class="text-decoration-none">
                                    {{ $achivement['title'] }}
                                </a>
                            </td>
                            <td class="text-nowrap">
                                {{ $achivement['date'] }}
                            </td>
                            <td>
                                @switch($achivement['status'])
                                    @case('review')
                                    <span class="badge bg-warning text-dark">На проверке</span>
                                    @break
                                    @case('approved')
                                    <span class="badge bg-success">Принято</span>
                                    @break
                                    @case('rejected')
                                    <span class="badge bg-danger">Отклонено</span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary">Неизвестно</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Нет достижений</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-center bg-white">
                <div class="small text-muted">
                    Показано {{ count($achivments) }} из {{ $totalAchivments }}
                </div>
                @if(count($achivments) < $totalAchivments)
                    <button wire:click="loadMore" class="btn btn-outline-primary btn-sm mt-2">
                        Загрузить ещё
                    </button>
                @endif
            </div>
        </div>
    </div>



<div class=" col-md-12 card mt-4">
    <div class="card-header d-flex justify-content-between">
        <h5>График достижений за последние 6 месяцев</h5>

        <a href="{{route('reports')}}" class="btn btn-outline-primary btn-sm mt-2"> Перейти к отчетам</a>
    </div>
    <div class="card-body">
        <div id="achievements-chart"></div>
    </div>
</div>
</div>

@push('scripts')
    <script>
            console.log(123)
            const options = {
                series: @json($chartSeries),
                chart: { height: 300, type: 'area', toolbar: { show: false } },
                stroke: { curve: 'smooth' },
                dataLabels: { enabled: false },
                xaxis: {
                    type: 'datetime',
                    categories: @json($chartCategories)
                },
                tooltip: { x: { format: 'MMM yyyy' } },
                colors: ['#0d6efd']
            };

            const el = document.querySelector('#achievements-chart');
            if (el && typeof ApexCharts !== 'undefined') {
                new ApexCharts(el, options).render();
            }
    </script>
@endpush
