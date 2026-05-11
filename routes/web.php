<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductMediaController;
use App\Http\Controllers\Admin\WaitlistController as AdminWaitlistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\WaitlistController;
use Illuminate\Support\Facades\Route;

// Public Routes (Front-end)
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/p/{slug}', [FrontController::class, 'show'])->name('product.show');
Route::post('/waitlist', [WaitlistController::class, 'store'])->name('waitlist.store');

// Admin Routes (Protected)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
        Route::delete('product-media/{media}', [ProductMediaController::class, 'destroy'])->name('product-media.destroy');
        Route::get('waitlists', [AdminWaitlistController::class, 'index'])->name('waitlists.index');
    });
});

require __DIR__.'/auth.php';
