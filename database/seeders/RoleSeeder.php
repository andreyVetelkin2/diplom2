<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Преподаватель', 'slug' => 'teacher'],
            ['name' => 'Начальник кафедры', 'slug' => 'head_department'],
            ['name' => 'Начальник университета', 'slug' => 'head_institutes'],

        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }


    }
}
