<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;

Route::prefix('loans')->group(function () {
    Route::get('/', [LoanController::class, 'index']);
    Route::get('/member/{memberId}', [LoanController::class, 'byMember']);
    Route::get('/book/{bookId}', [LoanController::class, 'byBook']);
    Route::post('/', [LoanController::class, 'store']);
<<<<<<< Updated upstream
    Route::put('/{id}/return', [LoanController::class, 'returnBook']);
=======
    Route::put('/{id}/return', [LoanController::class, 'returnBook']); // ← hapus /loans
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/{id}', [LoanController::class, 'show']);
>>>>>>> Stashed changes
});
