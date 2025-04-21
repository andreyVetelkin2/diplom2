<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        $permission = Permission::where('slug', 'view-roles')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): Response
    {
        $permission = Permission::where('slug', 'view-roles')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        $permission = Permission::where('slug', 'create-roles')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        $permission = Permission::where('slug', 'edit-roles')->first();
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        $permission = Permission::where('slug', 'delete-roles')->first();

        // Проверяем, есть ли у пользователя разрешение на удаление этой роли
        return $user->hasPermissionTo($permission)
            ? Response::allow()
            : Response::denyAsNotFound();
    }


//    /**
//     * Determine whether the user can restore the model.
//     */
//    public function restore(User $user): Response
//    {
//        // Здесь можно добавить логику для восстановления ролей, если нужно
//        return Response::denyAsNotFound(); // Например, запрещаем восстановление
//    }
//
//    /**
//     * Determine whether the user can permanently delete the model.
//     */
//    public function forceDelete(User $user): Response
//    {
//        // Здесь можно добавить логику для окончательного удаления ролей, если нужно
//        return Response::denyAsNotFound(); // Например, запрещаем окончательное удаление
//    }
}


