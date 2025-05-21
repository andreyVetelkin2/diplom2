<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            ['name' => 'Просмотр пользователей', 'slug' => 'view-users'],
            ['name' => 'Создание пользователей', 'slug' => 'create-users'],
            ['name' => 'Редактирование пользователей', 'slug' => 'edit-users'],
            ['name' => 'Удаление пользователей', 'slug' => 'delete-users'],

            ['name' => 'Просмотр ролей', 'slug' => 'view-roles'],
            ['name' => 'Создание ролей', 'slug' => 'create-roles'],
            ['name' => 'Редактирование ролей', 'slug' => 'edit-roles'],
            ['name' => 'Удаление ролей', 'slug' => 'delete-roles'],

            ['name' => 'Просмотр прав', 'slug' => 'view-permissions'],
            ['name' => 'Создание прав', 'slug' => 'create-permissions'],
            ['name' => 'Редактирование прав', 'slug' => 'edit-permissions'],
            ['name' => 'Удаление прав', 'slug' => 'delete-permissions'],

            ['name' => 'Назначение прав ролям', 'slug' => 'assign-permissions-to-roles'],
            ['name' => 'Отзыв прав у ролей', 'slug' => 'revoke-permissions-from-roles'],
            ['name' => 'Назначение ролей пользователям', 'slug' => 'assign-roles-to-users'],
            ['name' => 'Отзыв ролей у пользователей', 'slug' => 'revoke-roles-from-users'],

            ['name' => 'Ревью достижений', 'slug' => 'review-forms'],
            ['name' => 'Управлять (кем-то\\чем-то)', 'slug' => 'manage'],
            ['name' => 'Редактирование шаблонов', 'slug' => 'template-edit'],
            ['name' => 'Редактирование форм', 'slug' => 'form-edit'],

            ['name' => 'Архив отчетов по кафедам', 'slug' => 'archive-report-on-the-departments'],
            ['name' => 'Архив отчетов по должностям', 'slug' => 'archive-report-on-positions'],
            ['name' => 'Архив отчетов по показателям', 'slug' => 'archive-report-on-forms'],
            ['name' => 'Архив отчетов по преподавателям', 'slug' => 'archive-report-on-users'],

            ['name' => 'Загрузка данных Google Scholar', 'slug' => 'update-Google-Scholar-users'],

            ['name' => 'Установка лимита баллов', 'slug' => 'installation-of-the-point-limit'],
            ['name' => 'Управление штрафными баллами', 'slug' => 'penalty-points-management'],
            ['name' => 'Загрузка достижений', 'slug' => 'loading-achievements'],
            ['name' => 'Пересчет рейтинга всем пользователям', 'slug' => 'loading-achievements'],

            ['name' => 'Отчетность по кафедрам', 'slug' => 'report-on-the-departments'],
            ['name' => 'Отчетность по кафедре', 'slug' => 'report-on-the-department'],
            ['name' => 'Отчет по пользователям', 'slug' => 'report-on-the-user'],
            ['name' => 'Отчет по показателям', 'slug' => 'report-on-the-forms'],
            ['name' => 'Отчет по должностям', 'slug' => 'report-on-the-position'],


        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name']]
            );
        }

    }
}
