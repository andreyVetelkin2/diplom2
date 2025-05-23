<div class="col-md-12 card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>График достижений </h5>
        <a href="{{ route('reports') }}" class="btn btn-outline-primary btn-sm">Перейти к отчетам</a>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="applyFilter" class="row mb-3">
            <div class="col-md-3">
                <label for="startDate">Дата начала</label>
                <input type="date" wire:model="startDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="endDate">Дата окончания</label>
                <input type="date" wire:model="endDate" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="selectedDepartment">Кафедра</label>
                <select wire:model="selectedDepartment" class="form-select">
                    <option value="">Все кафедры</option>
                    @foreach($departments as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Применить</button>
            </div>
        </form>


        <div id="achievements-chart"></div>
    </div>
</div>

@push('scripts')
    <script>
        const options = {
            series: @json($chartSeries),
            chart: {
                height: 300,
                type: 'bar', // Изменено с 'area' на 'bar'
                toolbar: {show: false}
            },
            plotOptions: {
                bar: {
                    horizontal: false, // Вертикальные столбцы (для горизонтальных - true)
                    columnWidth: '100%', // Ширина столбцов
                    endingShape: 'rounded' // Закругленные края
                }
            },
            dataLabels: {enabled: false},
            xaxis: {
                type: 'datetime',
                categories: @json($chartCategories)
            },
            tooltip: {
                x: {format: 'MMM yyyy'},
                y: {
                    formatter: function (val) {
                        return val // Можно добавить форматирование значений
                    }
                }
            },
            colors: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107']
        };

        const el = document.querySelector('#achievements-chart');
        if (el && typeof ApexCharts !== 'undefined') {
            new ApexCharts(el, options).render();
        }
    </script>
@endpush
