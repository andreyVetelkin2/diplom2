<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
     {
         $allRoles = Role::all();
         $allPermissions = Permission::all();

         // Создаем пользователя root
         $root = User::updateOrCreate(
             ['email' => 'root@root.root'],
             [
                 'name' => 'root',
                 'password' => bcrypt('123456789'),
             ]
         );

         // Присваиваем все роли
         $root->roles()->sync($allRoles);

         // Присваиваем все права
         $root->permissions()->sync($allPermissions);

         // Сохраняем изменения
         $root->save();
     }

}
