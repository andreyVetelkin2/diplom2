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
//     public function run()
//     {
// //        $developer = Role::where('slug','web-developer')->first();
// //        $manager = Role::where('slug', 'project-manager')->first();
// //        $createTasks = Permission::where('slug','create-tasks')->first();
// //        $manageUsers = Permission::where('slug','manage-users')->first();
// //        $user1 = new User();
// //        $user1->name = 'Jhon Deo';
// //        $user1->email = 'jhon@deo.com';
// //        $user1->password = bcrypt('secret');
// //        $user1->save();
// //        $user1->roles()->attach($developer);
// //        $user1->permissions()->attach($createTasks);
// //        $user2 = new User();
// //        $user2->name = 'Mike Thomas';
// //        $user2->email = 'mike@thomas.com';
// //        $user2->password = bcrypt('secret');
// //        $user2->save();
// //        $user2->roles()->attach($manager);
// //        $user2->permissions()->attach($manageUsers);
//     }
public function run()
{
    try {
        // Получаем или создаем роли
        $developer = Role::firstOrCreate([
            'slug' => 'web-developer',
            'name' => 'Web Developer'
        ]);

        $manager = Role::firstOrCreate([
            'slug' => 'project-manager',
            'name' => 'Project Manager'
        ]);

        // Получаем или создаем разрешения
        $createTasks = Permission::firstOrCreate([
            'slug' => 'create-tasks',
            'name' => 'Create Tasks'
        ]);

        $manageUsers = Permission::firstOrCreate([
            'slug' => 'manage-users',
            'name' => 'Manage Users'
        ]);

        // Создаем первого пользователя
        $user1 = User::firstOrCreate(
            ['email' => 'jhon@deo.com'],
            [
                'name' => 'Jhon Deo',
                'password' => bcrypt('secret'),
                'rating' => 0 // Добавляем обязательное поле
            ]
        );

        if (!$user1->roles()->where('id', $developer->id)->exists()) {
            $user1->roles()->attach($developer);
        }

        if (!$user1->permissions()->where('id', $createTasks->id)->exists()) {
            $user1->permissions()->attach($createTasks);
        }

        // Создаем второго пользователя
        $user2 = User::firstOrCreate(
            ['email' => 'mike@thomas.com'],
            [
                'name' => 'Mike Thomas',
                'password' => bcrypt('secret'),
                'rating' => 0 // Добавляем обязательное поле
            ]
        );

        if (!$user2->roles()->where('id', $manager->id)->exists()) {
            $user2->roles()->attach($manager);
        }

        if (!$user2->permissions()->where('id', $manageUsers->id)->exists()) {
            $user2->permissions()->attach($manageUsers);
        }

    } catch (\Exception $e) {
        \Log::error('Seeder error: '.$e->getMessage());
        throw $e;
    }
}
}
