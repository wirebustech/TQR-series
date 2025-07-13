<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;

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

// Public routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/blogs', [PublicController::class, 'blogs'])->name('blogs');
Route::get('/blog/{slug}', [PublicController::class, 'blog'])->name('blog');
Route::get('/webinars', [PublicController::class, 'webinars'])->name('webinars');
Route::get('/webinar/{id}', [PublicController::class, 'webinar'])->name('webinar');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy');

// Newsletter and donation submissions
Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::post('/donate', [PublicController::class, 'submitDonation'])->name('donate');

// API Documentation
Route::get('/api/docs', [App\Http\Controllers\Api\DocumentationController::class, 'index'])->name('api.docs');
Route::get('/api/v1/specification', [App\Http\Controllers\Api\DocumentationController::class, 'specification'])->name('api.specification');

// Sitemap
Route::get('/sitemap.xml', function() {
    $path = public_path('sitemap.xml');
    if (File::exists($path)) {
        return response()->file($path, ['Content-Type' => 'application/xml']);
    }
    return response('Sitemap not found', 404);
})->name('sitemap');

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
        Route::get('newsletter', [AdminController::class, 'newsletter'])->name('newsletter');
        Route::get('donations', [AdminController::class, 'donations'])->name('donations');
        Route::post('logout', [AdminController::class, 'logout'])->name('logout');
    });
});
