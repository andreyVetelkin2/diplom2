<?php

return [


    [
        'label' => 'Администрирование',
        'links' => [

            [
                'label' => 'Пользователи',
                'route' => 'users',
                'active' => 'admin/users',
            ],
            [
                'label' => 'Права',
                'route' => 'permissions',
                'active' => 'admin/permissions',
            ],
            [
                'label' => 'Роли',
                'route' => 'roles',
                'active' => 'admin/roles',
            ],
            [
                'label' => 'Институты',
                'route' => 'institutes',
                'active' => 'admin/institutes',
            ],
            [
                'label' => 'Кафедры',
                'route' => 'departments',
                'active' => 'admin/departments',
            ],

        ],
    ],

];
