<?php

use App\Livewire\CRUD\Permissions;
use App\Livewire\CRUD\RoleDetail;
use App\Livewire\CRUD\Roles;
use App\Livewire\CRUD\UserDetail;
use App\Livewire\CRUD\Users;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
| Посмотреть все доступные маршруты ->  php artisan route:list
*/





Route::middleware('auth')->group(function() { //группируем чтобы указать что посредник применяется к обоим группам

    Route::prefix('')->group(function (){//префикса нет юзаем только для получения метода груп
        Route::view('/', 'index')
            ->name('index');

        Route::view('upload', 'upload')
            ->name('upload');

        Route::view('profile', 'profile')
            ->name('profile');

        Route::view('templates', 'templates')
            ->name('templates');
    });


    Route::middleware('role:admin')->prefix('admin')->group(function() {//префикс добавляется так как оба маршрута лежат по пути /admin/../


        Route::prefix('users')->group(function() {
            Route::get('/', Users::class)
                ->name('users');
            Route::get('/{user}', UserDetail::class)//передаем сразу livewire компонент как вьюшку чтоб не искать пользователя по id руками
            ->name('user-detail');
        });

        Route::prefix('permissions')->group(function() {
            Route::get('/', Permissions::class)
                ->name('permissions');

        });

        Route::prefix('roles')->group(function() {
            Route::get('/', Roles::class)
                ->name('roles');
            Route::get('/{role}', RoleDetail::class)
            ->name('role-detail');
        });

    });
});



require __DIR__.'/web2.php';
require __DIR__.'/auth.php';
