<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\TransactionDashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PdfTextController;
use App\Http\Controllers\RideRequestController;
use App\Http\Controllers\ResourceReportController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [TransactionDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');

    Route::get('/resources/{id}', [ResourceController::class, 'show'])->name('resources.show');
    Route::get('/resources/{id}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{id}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{id}', [ResourceController::class, 'destroy'])->name('resources.destroy');

    Route::post('/resources/{id}/borrow', [BorrowRequestController::class, 'store'])->name('borrow.store');
    Route::post('/borrow-requests/{id}/approve', [BorrowRequestController::class, 'approve'])->name('borrow.approve');
    Route::post('/borrow-requests/{id}/reject', [BorrowRequestController::class, 'reject'])->name('borrow.reject');

    Route::post('/resources/{id}/review', [ReviewController::class, 'store'])->name('review.store');

    Route::get('/pdf-texts/create', [PdfTextController::class, 'create'])->name('pdf-texts.create');
    Route::post('/pdf-texts', [PdfTextController::class, 'store'])->name('pdf-texts.store');
    Route::get('/pdf-texts/{id}/edit', [PdfTextController::class, 'edit'])->name('pdf-texts.edit');
    Route::put('/pdf-texts/{id}', [PdfTextController::class, 'update'])->name('pdf-texts.update');
    Route::get('/pdf-texts/{id}/download', [PdfTextController::class, 'download'])->name('pdf-texts.download');

    Route::post('/resources/{id}/ride-request', [RideRequestController::class, 'store'])->name('ride.store');
    Route::post('/ride-requests/{id}/accept', [RideRequestController::class, 'accept'])->name('ride.accept');

    Route::post('/resources/{id}/report', [ResourceReportController::class, 'store'])->name('reports.store');

    Route::get('/admin/reports', [ResourceReportController::class, 'adminIndex'])->name('admin.reports');
    Route::post('/admin/reports/{id}/approve', [ResourceReportController::class, 'approve'])->name('admin.reports.approve');
    Route::post('/admin/reports/{id}/reject', [ResourceReportController::class, 'reject'])->name('admin.reports.reject');

    // 🏆 Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
});

require __DIR__.'/auth.php';