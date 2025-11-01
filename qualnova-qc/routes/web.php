<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisCacatController;
use App\Http\Controllers\DataCacatController;
use App\Http\Controllers\LaporanController;




// ─────────────────────────────────────────────
// 🔹 Public Route
// ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ─────────────────────────────────────────────
// 🔹 Protected Routes (Hanya bisa diakses jika login)
// ─────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

        // Users Management (CRUD)
        Route::resource('users', UserController::class);
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

        // Jenis Cacat (CRUD)
        Route::resource('jenis_cacat', JenisCacatController::class);

        // Quality Control (CRUD)
        Route::resource('data-cacat', DataCacatController::class)->except(['show']);
        
        //Pooling untuk Dahboard Realtime status
        Route::get('/dashboard/status-sistem', [DashboardController::class, 'statusSistemJson']);
       // CHart Tren
        Route::get('/dashboard/chart-trend', [DashboardController::class, 'trendChart'])->name('dashboard.chartTrend');

        Route::get('/laporan/download', [LaporanController::class, 'downloadPdf'])->name('laporan.download');

});

require __DIR__.'/auth.php';
