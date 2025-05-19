<?php

return [

    [
        'label' => 'Главная',
        'route' => 'index',
        'active' => '/',
    ],
    [
        'label' => 'Профиль',
        'route' => 'profile',
        'active' => 'profile',
    ],
    [

        'label' => 'Шаблоны',
        'route' => 'templates',
        'active' => 'templates',
        'permission' => 'template-edit'
    ],
    [
        'label' => 'Формы',
        'route' => 'forms',
        'active' => 'forms',
        'permission' => 'form-edit',
    ],
    [
        'label' => 'Отчеты',
        'route' => 'reports',
        'active' => 'reports',
    ],
    [
        'label' => 'Архив отчетов',
        'route' => 'reports-archive',
        'active' => 'reports-archive',
    ],

    [
        'label' => 'Загрузить достижение',
        'route' => 'upload',
        'active' => 'upload',
    ],

    [

        'label' => 'Публикации',
        'route' => 'author.prep',
        'active' => 'author',
    ],
    [
        'label' => 'Настройки Google Scholar ',
        'route' => 'scholar',
        'active' => 'scholar',
    ],
    [
            'label' => 'Загрузка данных Google',
            'route' => 'users.index',
            'active' => 'usersgoogle',
            'permission' => 'update-Google-Scholar-users'
        ],


];
