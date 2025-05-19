
<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $author->name }} - Academic Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
       <div class="bg-white rounded-lg shadow-md p-6 mb-6">
               <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                   <div class="flex flex-col md:flex-row items-start md:items-center">
                       <div class="mb-4 md:mb-0 md:mr-6">
                           <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                               <i class="fas fa-user text-4xl"></i>
                           </div>
                       </div>
                       <div class="flex-1">
                           <h1 class="text-3xl font-bold text-gray-800">{{ $author->name }}</h1>
                           <p class="text-gray-600 mt-1">{{ $author->affiliation }}</p>
                           @if($author->email)
                               <p class="text-blue-500 mt-1"><i class="fas fa-envelope mr-2"></i>{{ $author->email }}</p>
                           @endif
                           <div class="mt-3">
                               <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold mr-2">
                                   <i class="fas fa-quote-right mr-1"></i> Число цитирований: {{ number_format($author->cited_by) }}
                               </span>
                               <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold mr-2">
                                   <i class="fas fa-chart-bar mr-1"></i> Индекс хирша: {{ $h_index }}
                               </span>

                           </div>
                       </div>
                   </div>

                   @if(!empty($author->interests))
                       @php
                           // Декодируем JSON и преобразуем в массив, если это строка
                           $interests = is_string($author->interests)
                               ? json_decode($author->interests, true)
                               : $author->interests;

                           // Проверяем, что это массив объектов с полем title
                           $hasTitleKey = isset($interests[0]['title']);
                       @endphp

                       <div class="mt-6 pt-6 border-t border-gray-200">
                           <h3 class="text-lg font-semibold text-gray-800 mb-3">Научные интересы</h3>
                           <div class="flex flex-wrap gap-2">
                               @foreach($interests as $interest)
                                   @if($hasTitleKey)
                                       <a href="{{ $interest['link'] ?? '#' }}"
                                          target="_blank"
                                          class="bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-full text-sm text-gray-700 transition-colors">
                                           {{ $interest['title'] }}
                                       </a>
                                   @else
                                       <span class="bg-gray-100 px-3 py-1 rounded-full text-sm text-gray-700">
                                           {{ is_array($interest) ? json_encode($interest) : $interest }}
                                       </span>
                                   @endif
                               @endforeach
                           </div>
                       </div>
                   @endif
               </div>
           </div>


        <!-- Citations Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Цитаты с течением времени</h2>
            <div class="h-80">
                <canvas id="citationsChart"></canvas>
            </div>
        </div>


        <!-- Publications Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Публикации</h2>
                <div class="text-gray-600">
                    Отображаются {{ $articles->firstItem() }} - {{ $articles->lastItem() }} из {{ $articles->total() }}
                </div>
            </div>




                            <div class="space-y-6">
                                @foreach($articles as $article)
                                <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                    <h3 class="text-xl font-semibold text-blue-700 hover:text-blue-900">
                                        <a href="{{ $article['link'] ?? '#' }}" target="_blank">{{ $article['title'] }}</a>
                                    </h3>
                                    <p class="text-gray-600 mt-1">
                                        {{ $article['authors'] ?? '' }} -
                                        <span class="font-medium">{{ $article['publication'] ?? '' }}</span>,
                                        {{ $article['year'] ?? '' }}
                                    </p>
                                    @if(isset($article['citation_id']))
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-quote-right mr-1"></i> Колличество цитат: {{ $article['cited_by']['value'] ?? 0 }}
                                        </p>
                                    @endif
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        @if(isset($article['link']))
                                            <a href="{{ $article['link'] }}" target="_blank" class="mr-3 text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-external-link-alt mr-1"></i> Просмотр в браузере
                                            </a>
                                        @endif

                                    </div>
                                </div>
                                @endforeach

            </div>

           <div class="pagination">
               @if ($articles->onFirstPage())
                   <span>&laquo;</span>
               @else
                   <a href="{{ $articles->previousPageUrl() }}" rel="prev">&laquo;</a>
               @endif

               @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                   @if ($page == $articles->currentPage())
                       <span class="active"><b>{{ $page }}</b></span>
                   @else
                       <a href="{{ $url }}">{{ $page }}</a>
                   @endif
               @endforeach

               @if ($articles->hasMorePages())
                   <a href="{{ $articles->nextPageUrl() }}" rel="next">&raquo;</a>
               @else
                   <span>&raquo;</span>
               @endif
           </div>

           <style>
               .pagination {
                   display: flex;
                   justify-content: center; /* Центрирование по горизонтали */
                   align-items: center; /* Центрирование по вертикали (если нужно) */
                   margin-top: 20px; /* Добавьте отступ сверху */
               }

               .pagination a, .pagination span {
                   display: inline-block; /* Отображать как строчные элементы, но с возможностью задания размеров */
                   padding: 5px 10px;
                   margin: 0 5px; /* Отступы между элементами */
                   border: 1px solid #ccc;
                   text-decoration: none;
                   color: #333;
                   border-radius: 3px; /* Скругление углов */
               }

               .pagination .active {
                   border: 1px solid #007bff; /* Цвет активной страницы */
                   background-color: #007bff; /* Цвет фона активной страницы */
                   color: white; /* Цвет текста активной страницы */
               }

               .pagination .active b { /* Убедитесь, что жирный текст тоже имеет нужный цвет */
                   color: white;
               }

               .pagination a:hover {
                   background-color: #f0f0f0;
               }
           </style>

        </div>
    </div>

    <script>
        // Инициализация графика
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('citationsChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartData['years']),
                    datasets: [{
                        label: 'Количество цитат в год',
                        data: @json($chartData['citations']),
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 0.8)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Количество цитирований'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Год'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
</x-app-layout>
