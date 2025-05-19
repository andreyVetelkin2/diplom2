
<?php

use App\Http\Controllers\AuthorController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/author', [AuthorController::class, 'show'])->name('author.show');
Route::get('/search', [AuthorController::class, 'prep'])->name('author.prep');
Route::post('/choice', [AuthorController::class, 'findAuthor'])->name('authors.index');

Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');


//Route::get('/authors/find/{mauthors}', [AuthorController::class, 'findAuthor'])->name('authors.index');
Route::post('/authors/select', [AuthorController::class, 'select'])->name('authors.select');



Route::middleware('auth')->group(function() { //группируем чтобы указать что посредник применяется к обоим группам

        Route::view('scholar', 'scholar')
            ->name('scholar');
    });

Route::post('/users/{user}/update-author-id', [UserController::class, 'updateAuthorId'])
     ->name('users.update-author-id');

Route::get('/usersgoogle', [UserController::class, 'index'])->name('users.index');
//Route::post('/usersgoogle/{user}/update-author-id', [UserController::class, 'updateAuthorId'])->name('users.update-author-id');
Route::post('/usersgoogle/fetch-google-scholar', [UserController::class, 'fetchGoogleScholarData'])->name('users.fetch-google-scholar');
Route::post('/usersgoogle/upload-data', [UserController::class, 'uploadData'])->name('users.upload-data');
Route::get('/download/{filename}', [UserController::class, 'downloadFile'])->name('download.file'); // Перенесено в контроллер
