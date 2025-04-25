<?php


namespace App\Services;


use App\Interfaces\BreadcrumbsFromUrlInterface;
use Illuminate\Support\Facades\Request;

class BreadcrumbsService implements BreadcrumbsFromUrlInterface
{

    public function generate():array
    {
        $path = Request::path(); // Получаем путь URL (например, 'users/profile/edit')
        $segments = array_filter(explode('/', $path)); // Разбиваем путь на сегменты и удаляем пустые

        $breadcrumbs = [];
        $currentUrl = '';

        // 1. Добавляем "корень" или "домашнюю" страницу
        $breadcrumbs[] = [
            'text' => __('Home'), // Используем хелпер для возможного перевода
            'url' => url('/'),
            'is_last' => empty($segments) // Домашняя будет последней, только если нет других сегментов
        ];

        // Если есть сегменты, домашняя страница уже не последняя
        if (!empty($segments)) {
            $breadcrumbs[0]['is_last'] = false;
        }

        // 2. Проходим по сегментам URL
        foreach ($segments as $index => $segment) {
            // Пропускаем индексный файл, если он есть в пути (редко, но возможно)
            if (strtolower($segment) === 'index' || $segment === '') {
                continue;
            }

            $currentUrl .= '/' . $segment; // Накапливаем URL
            $isLast = $index === count($segments) - 1; // Проверяем, последний ли это сегмент

            $breadcrumbs[] = [
                // Форматируем текст: заменяем '-' и '_' на пробелы, делаем заглавными первые буквы слов
                'text' => ucwords(str_replace(['-', '_'], ' ', $segment)),
                'url' => url($currentUrl),
                'is_last' => $isLast
            ];
        }

        return $breadcrumbs;
    }
}
