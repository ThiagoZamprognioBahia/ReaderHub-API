<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PublicReaderController;
use Illuminate\Support\Facades\Route;

// Unauthenticated routes
Route::namespace('Auth')->group(function () {

    Route::post('/login', [AuthController::class, 'auth']);
    Route::post('/check-in', [PublicReaderController::class, 'store']);
});

// Authenticated routes
Route::namespace('API')->middleware('auth:sanctum')->group(function () {

    Route::apiResource('/genres', GenreController::class);
    Route::apiResource('/publishers', PublisherController::class);
    Route::apiResource('/books', BookController::class);
    Route::apiResource('/books-readers', BookReaderController::class);
    Route::apiResource('/readers', ReaderController::class);
    Route::get('/cache/{id}', [ReaderController::class, 'getTotalBooksAndPagesFromCache']);
});
