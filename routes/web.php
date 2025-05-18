<?php

use App\Livewire\CRUD\Departments;
use App\Livewire\CRUD\Permissions;
use App\Livewire\CRUD\Positions;
use App\Livewire\CRUD\RoleDetail;
use App\Livewire\CRUD\Roles;
use App\Livewire\CRUD\UserDetail;
use App\Livewire\CRUD\Users;
use App\Livewire\ManageForms;
<<<<<<< Updated upstream
=======
use App\Livewire\ManagerCabinet;
>>>>>>> Stashed changes
use App\Livewire\ManageTemplates;
use App\Livewire\Reports;
use App\Livewire\UserFillForm;
use App\Livewire\CRUD\Institutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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




Route::middleware('auth')->group(function () { //группируем чтобы указать что посредник применяется к обоим группам

    Route::prefix('')->group(function () {//префикса нет юзаем только для получения метода груп

<<<<<<< Updated upstream
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
=======
        Route::get('upload', UserFillForm::class)
            ->name('upload');

        Route::get('reports', Reports::class)
            ->name('reports');

        Route::get('reports-archive', \App\Livewire\ReportArchive::class)
            ->name('reports-archive');
>>>>>>> Stashed changes
    });


    Route::middleware('role:,manage')->group(function () {
        Route::get('/manager-cabinet', ManagerCabinet::class)
            ->name('manager-cabinet');
    });

    Route::middleware('role:,template-edit')->group(function () {
        Route::get('templates', ManageTemplates::class)
            ->name('templates');
    });

    Route::middleware('role:,form-edit')->group(function () {
        Route::get('forms', ManageForms::class)
            ->name('forms');
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {//префикс добавляется так как оба маршрута лежат по пути /admin/../

        Route::get('/', fn() => redirect()->route('index'))->name('admin');

        Route::prefix('users')->group(function () {
            Route::get('/', Users::class)
                ->name('users');
            Route::get('/{user}', UserDetail::class)//передаем сразу livewire компонент как вьюшку чтоб не искать пользователя по id руками
            ->name('user-detail');
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/', Permissions::class)
                ->name('permissions');

        });

<<<<<<< Updated upstream
        Route::prefix('departments')->group(function() {
=======
        Route::prefix('departments')->group(function () {
>>>>>>> Stashed changes
            Route::get('/', Departments::class)
                ->name('departments');

        });

<<<<<<< Updated upstream
        Route::prefix('institutes')->group(function() {
=======
        Route::prefix('institutes')->group(function () {
>>>>>>> Stashed changes
            Route::get('/', Institutes::class)
                ->name('institutes');

        });

<<<<<<< Updated upstream
        Route::prefix('roles')->group(function() {
=======
        Route::prefix('positions')->group(function () {
            Route::get('/', Positions::class)
                ->name('positions');

        });
        Route::prefix('limit')->group(function () {
            Route::get('/', Positions::class)
                ->name('limit');

        });

        Route::prefix('roles')->group(function () {
>>>>>>> Stashed changes
            Route::get('/', Roles::class)
                ->name('roles');
            Route::get('/{role}', RoleDetail::class)
                ->name('role-detail');
        });

    });


    Route::get('/download-report/{filename}', function ($filename) {
        $userId = auth()->id();
        $path = storage_path("app/exports/reports/{$userId}/{$filename}");

        if (!file_exists($path)) {
            abort(404);
        }

        // Очистка буфера перед отправкой файла (важно!)
        if (ob_get_level()) {
            ob_end_clean();
        }

        return response()->streamDownload(function () use ($path) {
            readfile($path);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . rawurlencode($filename) . '"',
            'Content-Length' => filesize($path),
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    })->where('filename', '.*')->middleware('auth')->name('download.report');

});


<<<<<<< Updated upstream

require __DIR__.'/web2.php';
require __DIR__.'/auth.php';
=======
require __DIR__ . '/web2.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/scholar.php';
>>>>>>> Stashed changes
