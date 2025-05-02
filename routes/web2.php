<?php

use App\Livewire\Profile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes for Lesha so that there are no conflicts and each rule its own file
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('auth')->group(function() { //группируем чтобы указать что посредник применяется к обоим группам

    Route::prefix('')->group(function () {//префикса нет юзаем только для получения метода груп

        Route::get('profile', Profile::class)
            ->name('profile');
    });
});
