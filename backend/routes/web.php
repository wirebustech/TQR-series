<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public admin routes
    Route::get('login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminController::class, 'login']);
    
    // Protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('webinars', [AdminController::class, 'webinars'])->name('webinars');
        Route::get('contributions', [AdminController::class, 'contributions'])->name('contributions');
        Route::get('analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::get('pages', [AdminController::class, 'pages'])->name('pages');
        Route::get('blogs', [AdminController::class, 'blogs'])->name('blogs');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    });
});

// Redirect root to admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});
