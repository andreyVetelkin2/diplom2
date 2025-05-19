<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
       $this->call(RoleSeeder::class);
       $this->call(PermissionSeeder::class);
       $this->call(UserSeeder::class);
       $this->call(ScientificAchievementFormSeeder::class);
       $this->call(FormTemplateSeeder::class);
//        $this->call(TemplateFieldsSeeder::class);
//        $this->call(FormsSeeder::class);
    }
}
