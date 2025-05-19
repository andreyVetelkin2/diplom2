<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
       $manager = new Role();
       $manager->name = 'Project Manager';
       $manager->slug = 'project-manager';
       $manager->save();
       $developer = new Role();
       $developer->name = 'Web Developer';
       $developer->slug = 'web-developer';
       $developer->save();



  //   Стандартные Роли, которые должны быть по умолчанию
       $admin = new Role();
       $admin->name = 'Admin';
       $admin->slug = 'admin';
       $admin->save();

       $guest = new Role();
       $guest->name = 'Guest';
       $guest->slug = 'guest';
       $guest->save();

       $user = new Role();
       $user->name = 'User';
       $user->slug = 'user';
       $user->save();


    }
}
