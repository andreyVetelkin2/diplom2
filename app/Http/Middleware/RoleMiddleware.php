<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $role
     * @param string|null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null, $permission = null)
    {
        $user = auth()->user();

        // Если оба заданы: и роль, и право
        if ($role && $permission) {
            if (!$user->hasRole($role) || !$user->can($permission)) {
                abort(404);
            }
        }
        // Только роль
        elseif ($role && !$permission) {
            if (!$user->hasRole($role)) {
                abort(404);
            }
        }
        // Только право (если роль пропущена, но право передано первым)
        elseif (!$role && $permission) {
            if (!$user->can($permission)) {
                abort(404);
            }
        }

        return $next($request);
    }
}

