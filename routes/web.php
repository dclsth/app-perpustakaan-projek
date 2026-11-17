<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\MemberNotificationController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('member')->name('member.')->middleware('member')->group(function () {
        Route::get('/', [MemberPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/books', [MemberPortalController::class, 'books'])->name('books.index');
        Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
        Route::post('/books/{book}/reviews', [BookController::class, 'storeReview'])->name('books.reviews.store');
        Route::get('/loans', [MemberPortalController::class, 'loans'])->name('loans.index');
        Route::get('/waiting-list', [MemberPortalController::class, 'waitingList'])->name('waiting-list.index');
        Route::post('/books/{book}/waiting-list', [MemberPortalController::class, 'joinWaitingList'])->name('waiting-list.store');
        Route::delete('/waiting-list/{entry}', [MemberPortalController::class, 'leaveWaitingList'])->name('waiting-list.destroy');
        Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [MemberNotificationController::class, 'markRead'])->name('notifications.read');
        Route::get('/profile', [MemberPortalController::class, 'profile'])->name('profile');
    });

    Route::middleware('staff')->group(function () {
        Route::resource('books', BookController::class);
        Route::resource('members', MemberController::class);
        Route::get('/loans/report', [LoanController::class, 'report'])->name('loans.report');
        Route::resource('loans', LoanController::class);
        Route::resource('loans', LoanController::class);
        Route::put('/loans/{id}/kembalikan', [LoanController::class, 'kembalikan'])
            ->name('loans.kembalikan');

        Route::middleware(['admin'])->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show']);
        });
    });
});
