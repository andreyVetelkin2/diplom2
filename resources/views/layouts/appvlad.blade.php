<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
          integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('css/overlayscrollbars.min.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }} "/>
    <link rel="stylesheet" href="{{ asset('css/apexcharts.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/jsvectormap.min.css') }}"/>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary sidebar-open app-loaded">

{{--            <livewire:layout.navigation />--}}

<div class="app-wrapper">
    <livewire:header :links="[
    ['url' => route('index'), 'name' => 'Главная'],

    ]"/>

    @php
        //$user = App\Models\User::find(7);
        //dd($user->hasRole('web-developer')); //вернёт true
        //dd($user->hasRole('project-manager')); //вернёт false
        //$user->givePermissionsTo('manage-users'); //выдаём разрешение
        //dd($user->hasPermission('manage-users')); //вернёт true
    @endphp
    <livewire:menu.left-menu/>

    <!--begin::App Main-->
    <main class="app-main">



        <!--begin::App Content-->
        <div class="app-content">
            {{ $slot }}

        </div>
        <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
            Copyright &copy; 2014-2024&nbsp;
            <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
</div>

{{-- Заменено CDN на локальный путь --}}
<script src="{{ asset('js/overlayscrollbars.browser.es6.min.js') }}"></script>
{{-- Заменено CDN на локальный путь --}}
<script src="{{ asset('js/popper.min.js') }}"></script>
{{-- Заменено CDN на локальный путь --}}
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/adminlte.js') }}"></script>
{{-- Заменено CDN на локальный путь --}}
<script src="{{ asset('js/Sortable.min.js') }}"></script>

{{-- Заменено CDN на локальный путь --}}
<script src="{{ asset('js/apexcharts.min.js') }}"></script>

{{-- Заменено CDN на локальный путь --}}
<script src="{{ asset('js/jsvectormap.min.js') }}"></script>
{{-- Заменено CDN на локальный путь (предполагается, что файл world.js находится в public/js/maps/) --}}
<script src="{{ asset('js/world.js') }}"></script>

<script>
    const connectedSortables = document.querySelectorAll('.connectedSortable');
    connectedSortables.forEach((connectedSortable) => {
        let sortable = new Sortable(connectedSortable, {
            group: 'shared',
            handle: '.card-header',
        });
    });

    const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
    cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = 'move';
    });
</script>
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>
<script>
    // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
    // IT'S ALL JUST JUNK FOR DEMO
    // ++++++++++++++++++++++++++++++++++++++++++

    const sales_chart_options = {
        series: [
            {
                name: 'Digital Goods',
                data: [28, 48, 40, 19, 86, 27, 90],
            },
            {
                name: 'Electronics',
                data: [65, 59, 80, 81, 56, 55, 40],
            },
        ],
        chart: {
            height: 300,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        legend: {
            show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
        },
        xaxis: {
            type: 'datetime',
            categories: [
                '2023-01-01',
                '2023-02-01',
                '2023-03-01',
                '2023-04-01',
                '2023-05-01',
                '2023-06-01',
                '2023-07-01',
            ],
        },
        tooltip: {
            x: {
                format: 'MMMM yy', // Исправлен формат даты для tooltip
            },
        },
    };

    // Проверка наличия элемента перед инициализацией графика
    const revenueChartEl = document.querySelector('#revenue-chart');
    if (revenueChartEl && typeof ApexCharts !== 'undefined') {
        const sales_chart = new ApexCharts(
            revenueChartEl,
            sales_chart_options,
        );
        sales_chart.render();
    } else {
        console.error("Element #revenue-chart not found or ApexCharts library is not loaded.");
    }
</script>
<script>
    // Проверка наличия элемента и библиотеки перед инициализацией карты
    const worldMapEl = document.querySelector('#world-map');
    if (worldMapEl && typeof jsVectorMap !== 'undefined') {
        const visitorsData = {
            US: 398, // USA
            SA: 400, // Saudi Arabia
            CA: 1000, // Canada
            DE: 500, // Germany
            FR: 760, // France
            CN: 300, // China
            AU: 700, // Australia
            BR: 600, // Brazil
            IN: 800, // India
            GB: 320, // Great Britain
            RU: 3000, // Russia
        };

        // World map by jsVectorMap
        const map = new jsVectorMap({
            selector: '#world-map',
            map: 'world',
            // Добавьте другие опции карты здесь, если необходимо
            // Например, настройка отображения данных посетителей:
            // series: {
            //     regions: [{
            //         values: visitorsData,
            //         scale: ['#C8EEFF', '#0071A4'],
            //         normalizeFunction: 'polynomial'
            //     }]
            // },
            // onRegionTooltipShow(event, tooltip, code) {
            //     tooltip.text(
            //         `${tooltip.text()} (${visitorsData[code]})`
            //     );
            // }
        });
    } else {
        console.error("Element #world-map not found or jsVectorMap library is not loaded.");
    }


    // Sparkline charts
    // Проверка наличия элементов и библиотеки перед инициализацией
    if (typeof ApexCharts !== 'undefined') {
        const sparkline1El = document.querySelector('#sparkline-1');
        if (sparkline1El) {
            const option_sparkline1 = {
                series: [
                    {
                        data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
                    },
                ],
                chart: {type: 'area', height: 50, sparkline: {enabled: true}},
                stroke: {curve: 'straight'},
                fill: {opacity: 0.3},
                yaxis: {min: 0},
                colors: ['#DCE6EC'],
            };
            const sparkline1 = new ApexCharts(sparkline1El, option_sparkline1);
            sparkline1.render();
        } else {
            console.error("Element #sparkline-1 not found.");
        }


        const sparkline2El = document.querySelector('#sparkline-2');
        if (sparkline2El) {
            const option_sparkline2 = {
                series: [
                    {
                        data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
                    },
                ],
                chart: {type: 'area', height: 50, sparkline: {enabled: true}},
                stroke: {curve: 'straight'},
                fill: {opacity: 0.3},
                yaxis: {min: 0},
                colors: ['#DCE6EC'],
            };
            const sparkline2 = new ApexCharts(sparkline2El, option_sparkline2);
            sparkline2.render();
        } else {
            console.error("Element #sparkline-2 not found.");
        }

        const sparkline3El = document.querySelector('#sparkline-3');
        if (sparkline3El) {
            const option_sparkline3 = {
                series: [
                    {
                        data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
                    },
                ],
                chart: {type: 'area', height: 50, sparkline: {enabled: true}},
                stroke: {curve: 'straight'},
                fill: {opacity: 0.3},
                yaxis: {min: 0},
                colors: ['#DCE6EC'],
            };
            const sparkline3 = new ApexCharts(sparkline3El, option_sparkline3);
            sparkline3.render();
        } else {
            console.error("Element #sparkline-3 not found.");
        }
    } else {
        console.error("ApexCharts library is not loaded.");
    }

</script>
</body>
</html>
