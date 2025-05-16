<div id="achievements-chart"
     data-chart='@json($chartData)'></div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.querySelector('#achievements-chart');
            if (!el) return;

            const data = JSON.parse(el.dataset.chart);

            const options = {
                chart: { type: 'bar', height: 350 },
                series: data.series,
                xaxis: { categories: data.years },
                plotOptions: { bar: { horizontal: false, columnWidth: '50%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top' }
            };

            if (typeof ApexCharts !== 'undefined') {
                new ApexCharts(el, options).render();
            }
        });
    </script>
@endpush
