<div class="container py-4">
    <div class="row mb-3">
        <div class="col-md-4 d-flex">
            <!-- Привязываем селект к свойству selectedChart -->
            <select class="form-select me-2" wire:model="selectedChart">
                <option value="publications_per_year">Публикации по годам</option>
                <option value="citations_per_year">Цитирования по годам</option>
                <option value="department_rating">Рейтинг по департаментам</option>
                <option value="status_distribution">Распределение статусов</option>
                <option value="top_authors">Топ-10 авторов</option>
                <option value="average_percent">Средний % участия</option>
                <option value="scatter_hirsh_citations">H-индекс vs Цитирования</option>
                <option value="interests_radar">Радар интересов</option>
                <option value="donut_categories">Донат категорий</option>
                <option value="penalty_points_chart">Штрафные баллы</option>
            </select>

            <!-- Кнопка, вызывающая updateChart() без dispatch -->
            <button class="btn btn-primary" wire:click="updateChart($event.target.previousElementSibling.value)">
                Применить
            </button>
        </div>
    </div>

    <!-- Блок для графика: Livewire не трогает его содержимое -->
    <div id="chart" wire:ignore></div>
</div>

@push('scripts')
    <script>
        // Вызов при первой загрузке страницы
        document.addEventListener('DOMContentLoaded', () => {
            renderChart();
        });

        // Хук, срабатывающий после каждого AJAX-ответа Livewire
        window.livewire.hook('message.processed', (message, component) => {
            // Обновляем график после любого апдейта
            renderChart();
        });

        function renderChart() {
            // Считываем данные, которые Livewire положил в глобальные переменные
            const options = @this.chartOptions;
            options.series = @this.chartSeries;

            if (window.multiChart) {
                window.multiChart.updateOptions(options);
            } else {
                window.multiChart = new ApexCharts(
                    document.querySelector('#chart'),
                    options
                );
                window.multiChart.render();
            }
        }
    </script>
@endpush
