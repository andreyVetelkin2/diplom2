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
    public function run()
    {
        $ViewUser = new Permission();
        $ViewUser->name = 'View users';
        $ViewUser->slug = 'view-users';
        $ViewUser->save();

        $editUser = new Permission();
        $editUser->name = 'Edit users';
        $editUser->slug = 'edit-users';
        $editUser->save();

        $editUser = new Permission();
        $editUser->name = 'Delete users';
        $editUser->slug = 'delete-users';
        $editUser->save();

        $createUser = new Permission();
        $createUser->name = 'Create users';
        $createUser->slug = 'create-users';
        $createUser->save();
    }
}
