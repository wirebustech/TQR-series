<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\MediaLibraryController;
use App\Http\Controllers\Api\SocialMediaLinkController;
use App\Http\Controllers\Api\AffiliatePartnerController;
use App\Http\Controllers\Api\ExternalVideoController;
use App\Http\Controllers\Api\WebinarCourseController;
use App\Http\Controllers\Api\BetaSignupController;
use App\Http\Controllers\Api\ResearchContributionController;
use App\Http\Controllers\Api\SupportDonationController;
use App\Http\Controllers\Api\NewsletterSubscriptionController;
use App\Http\Controllers\Api\WebinarController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AnalyticsController;

// Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public webinars routes (for frontend display)
Route::get('webinars', [WebinarController::class, 'index']);
Route::get('webinars/{webinar}', [WebinarController::class, 'show']);
Route::get('webinars/stats', [WebinarController::class, 'stats']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // Resource routes (CRUD)
    Route::apiResource('pages', PageController::class);
    Route::apiResource('sections', SectionController::class);
    Route::apiResource('blogs', BlogController::class);
    Route::apiResource('media-library', MediaLibraryController::class);
    Route::apiResource('social-media-links', SocialMediaLinkController::class);
    Route::apiResource('affiliate-partners', AffiliatePartnerController::class);
    Route::apiResource('external-videos', ExternalVideoController::class);
    Route::apiResource('webinar-courses', WebinarCourseController::class);
    Route::apiResource('beta-signups', BetaSignupController::class);
    Route::apiResource('research-contributions', ResearchContributionController::class);
    Route::apiResource('support-donations', SupportDonationController::class);
    Route::apiResource('newsletter-subscriptions', NewsletterSubscriptionController::class);
    
    // Webinars (admin operations)
    Route::post('webinars', [WebinarController::class, 'store']);
    Route::put('webinars/{webinar}', [WebinarController::class, 'update']);
    Route::delete('webinars/{webinar}', [WebinarController::class, 'destroy']);
    Route::post('webinars/bulk-action', [WebinarController::class, 'bulkAction']);
    
    // Users (admin operations)
    Route::apiResource('users', UserController::class);
    Route::get('users/stats', [UserController::class, 'stats']);
    Route::post('users/bulk-action', [UserController::class, 'bulkAction']);
    
    // Analytics (admin operations)
    Route::prefix('analytics')->group(function () {
        Route::get('user-growth', [AnalyticsController::class, 'getUserGrowth']);
        Route::get('user-distribution', [AnalyticsController::class, 'getUserDistribution']);
        Route::get('webinar-performance', [AnalyticsController::class, 'getWebinarPerformance']);
        Route::get('contribution-status', [AnalyticsController::class, 'getContributionStatus']);
        Route::get('recent-activity', [AnalyticsController::class, 'getRecentActivity']);
        Route::get('top-content', [AnalyticsController::class, 'getTopContent']);
        Route::get('overview', [AnalyticsController::class, 'getOverview']);
        Route::get('export-report', [AnalyticsController::class, 'exportReport']);
    });
    
    // User profile (authenticated users)
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
});
