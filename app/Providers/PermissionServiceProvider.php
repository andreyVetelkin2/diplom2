<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * Пример использования
     *  @can('edit-users')
        <button>Редактировать пользователя</button>
        @endcan
     *
     * или
     *
     * if (auth()->user()->can('edit-users')) {
        // Логика редактирования
        }
     */
    public function boot()
    {
        //Пример вернёт true для текущего пользователя, если ему дано право управлять пользователями

        //Провайдер позволяет использовать стандартную директиву и метод can.
        //Gate::allows('manage-users');
        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
