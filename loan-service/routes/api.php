<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;

Route::prefix('loans')->group(function () {
    Route::get('/', [LoanController::class, 'index']);
    Route::get('/member/{memberId}', [LoanController::class, 'byMember']);
    Route::get('/book/{bookId}', [LoanController::class, 'byBook']);
    Route::post('/', [LoanController::class, 'store']);
    Route::put('/{id}/return', [LoanController::class, 'returnBook']); // ← hapus /loans
});