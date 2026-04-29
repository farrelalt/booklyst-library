<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::post('/members', [DashboardController::class, 'storeMember']);
Route::post('/books', [DashboardController::class, 'storeBook']);
Route::post('/loans', [DashboardController::class, 'storeLoan']);
Route::put('/loans/{id}/return', [DashboardController::class, 'returnLoan']);