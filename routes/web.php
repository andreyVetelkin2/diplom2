<?php

use App\Livewire\CRUD\Departments;
use App\Livewire\CRUD\Permissions;
use App\Livewire\CRUD\RoleDetail;
use App\Livewire\CRUD\Roles;
use App\Livewire\CRUD\UserDetail;
use App\Livewire\CRUD\Users;
use App\Livewire\ManageForms;
use App\Livewire\ManageTemplates;
use App\Livewire\Reports;
use App\Livewire\UserFillForm;
use App\Livewire\CRUD\Institutes;
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

        Route::get('upload', UserFillForm::class)
            ->name('upload');

//        Route::view('profile', 'profile')
//            ->name('profile');

        Route::get('templates', ManageTemplates::class)
            ->name('templates');

        Route::get('forms', ManageForms::class)
            ->name('forms');

        Route::get('reports', Reports::class)
            ->name('reports');
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

        Route::prefix('departments')->group(function() {
            Route::get('/', Departments::class)
                ->name('departments');

        });

        Route::prefix('institutes')->group(function() {
            Route::get('/', Institutes::class)
                ->name('institutes');

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
require __DIR__.'/scholar.php';
