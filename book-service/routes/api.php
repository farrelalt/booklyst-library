<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}/borrowers', [BookController::class, 'bookBorrowers']);
Route::patch('/books/{id}/stock', [BookController::class, 'updateStock']);