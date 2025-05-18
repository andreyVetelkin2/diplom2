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
<<<<<<< Updated upstream
=======
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
>>>>>>> Stashed changes
        'label' => 'Отчеты',
        'route' => 'reports',
        'active' => 'reports',
    ],
<<<<<<< Updated upstream
=======
    [
        'label' => 'Архив отчетов',
        'route' => 'reports-archive',
        'active' => 'reports-archive',
    ],

>>>>>>> Stashed changes
    [
        'label' => 'Загрузить достижение',
        'route' => 'upload',
        'active' => 'upload',
    ],

    [
<<<<<<< Updated upstream
        'label' => 'Шаблоны',
        'route' => 'templates',
        'active' => 'templates',
    ],
    [
        'label' => 'Формы',
        'route' => 'forms',
        'active' => 'forms',
    ],
=======
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

>>>>>>> Stashed changes
];
