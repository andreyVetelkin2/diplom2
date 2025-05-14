<div class="row gy-4">

    <div class="col-12">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Header -->
            <div class="card-header bg-white border-0 pb-2 pt-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-trophy-fill text-warning fs-4 me-2"></i>
                        <h5 class="d-inline fw-bold mb-0">Новые достижения сотрудников</h5>
                        <p class="text-muted small mb-0">Требуют подтверждения</p>
                    </div>

                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive px-4">
                <table class="table table-borderless table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-uppercase text-secondary small">Название</th>
                        <th class="text-uppercase text-secondary small">Дата</th>
                        <th class="text-uppercase text-secondary small text-center">Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($achivments as $achivement)
                        <tr class="border-bottom">
                            <td>
                                <a href="{{ route('form-entry', $achivement['id']) }}"
                                   class="text-decoration-none text-dark fw-semibold">
                                    {{ $achivement['title'] }}
                                </a>
                            </td>
                            <td class="text-nowrap text-muted small">
                                {{ \Carbon\Carbon::parse($achivement['date'])->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                @switch($achivement['status'])
                                    @case('review')
                                    <span
                                        class="badge rounded-pill bg-warning-subtle text-warning fw-semibold px-3 py-1">На проверке</span>
                                    @break
                                    @case('approved')
                                    <span
                                        class="badge rounded-pill bg-success-subtle text-success fw-semibold px-3 py-1">Принято</span>
                                    @break
                                    @case('rejected')
                                    <span class="badge rounded-pill bg-danger-subtle text-danger fw-semibold px-3 py-1">Отклонено</span>
                                    @break
                                    @default
                                    <span
                                        class="badge rounded-pill bg-secondary-subtle text-secondary fw-semibold px-3 py-1">Неизвестно</span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted fw-light">Нет достижений</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="card-footer bg-white border-0 text-center py-3">
                <small class="text-muted">Показано {{ count($achivments) }} из {{ $totalAchivments }}</small>
                @if(count($achivments) < $totalAchivments)
                    <div class="mt-3">
                        <button wire:click="loadMore"
                                class="btn btn-primary btn-sm rounded-pill px-4"
                                style="transition: transform .2s;"
                                onmouseover="this.style.transform='scale(1.05)';"
                                onmouseout="this.style.transform='scale(1)';">
                            <i class="bi bi-arrow-down-circle me-1 fs-5 align-middle"></i>
                            Загрузить ещё
                        </button>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <livewire:penalty-points-manager />
    <livewire:multi-chart-manager />

{{--    <div class=" col-md-12 card mt-4">--}}
{{--        <div class="card-header d-flex justify-content-between">--}}
{{--            <h5>График достижений за последние 6 месяцев</h5>--}}

{{--            <a href="{{route('reports')}}" class="btn btn-outline-primary btn-sm mt-2"> Перейти к отчетам</a>--}}
{{--        </div>--}}
{{--        <div class="card-body">--}}
{{--            <div id="achievements-chart"></div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>

@push('scripts')
{{--    <script>--}}
{{--        console.log(123)--}}
{{--        const options = {--}}
{{--            series: @json($chartSeries),--}}
{{--            chart: {height: 300, type: 'area', toolbar: {show: false}},--}}
{{--            stroke: {curve: 'smooth'},--}}
{{--            dataLabels: {enabled: false},--}}
{{--            xaxis: {--}}
{{--                type: 'datetime',--}}
{{--                categories: @json($chartCategories)--}}
{{--            },--}}
{{--            tooltip: {x: {format: 'MMM yyyy'}},--}}
{{--            colors: ['#0d6efd']--}}
{{--        };--}}

{{--        const el = document.querySelector('#achievements-chart');--}}
{{--        if (el && typeof ApexCharts !== 'undefined') {--}}
{{--            new ApexCharts(el, options).render();--}}
{{--        }--}}
{{--    </script>--}}
@endpush
