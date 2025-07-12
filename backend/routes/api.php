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

// Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

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
});
