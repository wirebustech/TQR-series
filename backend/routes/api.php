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
use App\Http\Controllers\Api\AdvancedAnalyticsController;
use App\Http\Controllers\Api\SitemapController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\OpportunityController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ContentManagementController;

// Health and status endpoints
Route::get('health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

Route::get('status', function () {
    return response()->json([
        'status' => 'operational',
        'services' => [
            'database' => 'connected',
            'cache' => 'operational',
            'queue' => 'operational'
        ]
    ]);
});

// Public articles routes
Route::get('articles', [BlogController::class, 'index']);
Route::get('articles/{article}', [BlogController::class, 'show']);
Route::get('articles/stats', [BlogController::class, 'stats']);

// Public opportunities routes (for news reel)
Route::get('opportunities', [OpportunityController::class, 'index']);
Route::get('opportunities/latest', [OpportunityController::class, 'latest']);
Route::get('opportunities/{opportunity}', [OpportunityController::class, 'show']);
Route::get('opportunities/stats', [OpportunityController::class, 'stats']);

// Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public webinars routes (for frontend display)
Route::get('webinars', [WebinarController::class, 'index']);
Route::get('webinars/{webinar}', [WebinarController::class, 'show']);
Route::get('webinars/stats', [WebinarController::class, 'stats']);

// Public sitemap routes
Route::get('sitemap/status', [SitemapController::class, 'status']);
Route::get('sitemap/stats', [SitemapController::class, 'stats']);

// Advanced Search Routes
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'search']);
    Route::get('/filters', [SearchController::class, 'getFilters']);
    Route::post('/track', [SearchController::class, 'trackSearch']);
    Route::get('/analytics', [SearchController::class, 'getAnalytics'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // User Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [UserDashboardController::class, 'getDashboard']);
        Route::post('/update-last-seen', [UserDashboardController::class, 'updateLastSeen']);
        Route::get('/learning-path', [UserDashboardController::class, 'getLearningPath']);
    });

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
    
    // Opportunities (admin operations)
    Route::post('opportunities', [OpportunityController::class, 'store']);
    Route::put('opportunities/{opportunity}', [OpportunityController::class, 'update']);
    Route::delete('opportunities/{opportunity}', [OpportunityController::class, 'destroy']);
    
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
    
    // Advanced Analytics (admin operations)
    Route::prefix('advanced-analytics')->group(function () {
        Route::get('overview', [AdvancedAnalyticsController::class, 'getOverview']);
        Route::get('users', [AdvancedAnalyticsController::class, 'getUserAnalytics']);
        Route::get('content', [AdvancedAnalyticsController::class, 'getContentAnalytics']);
        Route::get('financial', [AdvancedAnalyticsController::class, 'getFinancialAnalytics']);
        Route::get('real-time', [AdvancedAnalyticsController::class, 'getRealTimeAnalytics']);
        Route::get('export', [AdvancedAnalyticsController::class, 'exportReport']);
    });
    
    // Sitemap (admin operations)
    Route::prefix('sitemap')->group(function () {
        Route::post('generate', [SitemapController::class, 'generate']);
        Route::post('validate', [SitemapController::class, 'validate']);
    });
    
    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('webinar', [PaymentController::class, 'createWebinarPayment']);
        Route::post('donation', [PaymentController::class, 'createDonation']);
        Route::post('subscription', [PaymentController::class, 'createSubscription']);
        Route::post('confirm', [PaymentController::class, 'confirmPayment']);
        Route::get('history', [PaymentController::class, 'getPaymentHistory']);
        Route::get('methods', [PaymentController::class, 'getPaymentMethods']);
        Route::post('methods', [PaymentController::class, 'addPaymentMethod']);
        Route::delete('methods/{id}', [PaymentController::class, 'removePaymentMethod']);
    });

    // Content Management Routes (admin only)
    Route::prefix('content-management')->middleware('can:manage-content')->group(function () {
        Route::get('overview', [ContentManagementController::class, 'getOverview']);
        Route::post('bulk-action', [ContentManagementController::class, 'bulkAction']);
        Route::get('content', [ContentManagementController::class, 'getContent']);
        Route::post('content', [ContentManagementController::class, 'createContent']);
        Route::put('content/{id}', [ContentManagementController::class, 'updateContent']);
        Route::delete('content/{id}', [ContentManagementController::class, 'deleteContent']);
        Route::get('analytics', [ContentManagementController::class, 'getContentAnalytics']);
        Route::get('media-library', [ContentManagementController::class, 'getMediaLibrary']);
        Route::post('upload-media', [ContentManagementController::class, 'uploadMedia']);
    });
    
    // User profile (authenticated users)
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
});

// Webhook routes (no auth required)
Route::post('webhooks/stripe', [PaymentController::class, 'handleWebhook']);

// Beta waitlist (public)
Route::post('beta-waitlist', [BetaSignupController::class, 'store']);
