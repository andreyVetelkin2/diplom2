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

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        $permission = Permission::where('slug', 'view-users')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): Response
    {
        $permission = Permission::where('slug', 'view-users')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        $permission = Permission::where('slug', 'create-users')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        if($user->id == auth()->id()){
            return Response::allow();
        }

        $permission = Permission::where('slug', 'edit-users')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        $permission = Permission::where('slug', 'delete-users')->first();

        // Проверяем, есть ли у пользователя разрешение на удаление этой роли
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

}
