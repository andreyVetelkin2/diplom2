<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Права для управления пользователями
        $viewUser = new Permission();
        $viewUser->name = 'Просмотр пользователей';
        $viewUser->slug = 'view-users';
        $viewUser->save();

        $createUser = new Permission();
        $createUser->name = 'Создание пользователей';
        $createUser->slug = 'create-users';
        $createUser->save();

        $editUser = new Permission();
        $editUser->name = 'Редактирование пользователей';
        $editUser->slug = 'edit-users';
        $editUser->save();

        $deleteUser = new Permission();
        $deleteUser->name = 'Удаление пользователей';
        $deleteUser->slug = 'delete-users';
        $deleteUser->save();

        // Права для управления ролями
        $viewRole = new Permission();
        $viewRole->name = 'Просмотр ролей';
        $viewRole->slug = 'view-roles';
        $viewRole->save();

        $createRole = new Permission();
        $createRole->name = 'Создание ролей';
        $createRole->slug = 'create-roles';
        $createRole->save();

        $editRole = new Permission();
        $editRole->name = 'Редактирование ролей';
        $editRole->slug = 'edit-roles';
        $editRole->save();

        $deleteRole = new Permission();
        $deleteRole->name = 'Удаление ролей';
        $deleteRole->slug = 'delete-roles';
        $deleteRole->save();

        // Права для управления правами
        $viewPermission = new Permission();
        $viewPermission->name = 'Просмотр прав';
        $viewPermission->slug = 'view-permissions';
        $viewPermission->save();

        $createPermission = new Permission();
        $createPermission->name = 'Создание прав';
        $createPermission->slug = 'create-permissions';
        $createPermission->save();

        $editPermission = new Permission();
        $editPermission->name = 'Редактирование прав';
        $editPermission->slug = 'edit-permissions';
        $editPermission->save();

        $deletePermission = new Permission();
        $deletePermission->name = 'Удаление прав';
        $deletePermission->slug = 'delete-permissions';
        $deletePermission->save();

        // Права для назначения и отзыва прав у ролей
        $assignPermissions = new Permission();
        $assignPermissions->name = 'Назначение прав ролям';
        $assignPermissions->slug = 'assign-permissions-to-roles';
        $assignPermissions->save();

        $revokePermissions = new Permission();
        $revokePermissions->name = 'Отзыв прав у ролей';
        $revokePermissions->slug = 'revoke-permissions-from-roles';
        $revokePermissions->save();

        // Права для назначения и отзыва ролей у пользователей
        $assignRoles = new Permission();
        $assignRoles->name = 'Назначение ролей пользователям';
        $assignRoles->slug = 'assign-roles-to-users';
        $assignRoles->save();

        $revokeRoles = new Permission();
        $revokeRoles->name = 'Отзыв ролей у пользователей';
        $revokeRoles->slug = 'revoke-roles-from-users';
        $revokeRoles->save();
    }
}

