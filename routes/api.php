<?php

use App\Http\Controllers\Api\Auth\AuthApiController;
use App\Http\Controllers\API\Auth\PublicReaderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ReaderController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\PublisherController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BookReaderController;

// Unauthenticated routes
Route::namespace('Auth')->group(function() {

    Route::post('/login', [AuthApiController::class, 'auth']);
    Route::post('/registrar-se', [PublicReaderController::class, 'store']);

});

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('/generos', GenreController::class);
    Route::apiResource('/editoras', PublisherController::class);
    Route::apiResource('/livros', BookController::class);
    Route::apiResource('/livros-leitores', BookReaderController::class);
    Route::apiResource('/leitores', ReaderController::class);
    Route::get('/cache/{id}', [ReaderController::class, 'getTotalBooksAndPagesFromCache']);
    
});
