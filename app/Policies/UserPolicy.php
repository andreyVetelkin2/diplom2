<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user): Response
    {
//        $permission = Permission::where('slug', 'edit-users')->first();
//        return $user->hasPermissionTo($permission)
//            ? Response::allow()
//            : Response::denyAsNotFound();
        return Response::allow();
    }
}
