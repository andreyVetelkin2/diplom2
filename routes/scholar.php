
<?php

use App\Http\Controllers\AuthorController;


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
