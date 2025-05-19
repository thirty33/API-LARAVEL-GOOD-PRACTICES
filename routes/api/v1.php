<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\{
    LoginController,
    LogoutController,
    RegisterController,
};

use App\Http\Controllers\API\V1\Library\{
    AuthorController,
    GenreController,
    BookController,
    LoanController,
};

Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class)->name('register');
    Route::post('login', LoginController::class)->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', LogoutController::class)->name('logout');
    });
});

Route::prefix('library')->middleware('auth:sanctum')->group(function () {

    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('genres', GenreController::class);
    Route::apiResource('books', BookController::class);
    Route::patch('books/{book}/stock', [BookController::class, 'updateStock']);
    Route::apiResource('loans', LoanController::class)
        ->only(['index', 'store', 'show']);
    Route::patch('loans/{loan}/return', [LoanController::class, 'returnLoan']);
});
