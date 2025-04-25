<?php

namespace App\Services;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updatePassword(User $user, string $password): void
    {
        $user->update([
            'password' => Hash::make($password),
        ]);
    }

    public function syncRoles(User $user, array $roleIds): void
    {
        $user->roles()->sync($roleIds);
    }

    public function syncPermissions(User $user, array $permissionIds): void
    {
        $slugs = Permission::whereIn('id', $permissionIds)->pluck('slug')->toArray();
        $user->refreshPermissions(...$slugs);
    }
}
