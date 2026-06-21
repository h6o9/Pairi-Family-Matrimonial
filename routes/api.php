<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LookupController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ShortlistController;
use App\Http\Controllers\Api\SocialAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'app' => 'Pairi Family API',
        'version' => '1.1',
        'screens' => [
            'auth' => [
                'POST /api/register',
                'POST /api/verify-email-otp',
                'POST /api/resend-email-otp',
                'POST /api/login',
                'POST /api/social-login',
                'POST /api/forgot-password',
                'POST /api/verify-reset-otp',
                'POST /api/set-new-password',
                'POST /api/reset-password',
            ],
            'profile' => [
                'GET  /api/profile',
                'POST /api/profile/country',
                'POST /api/profile/basic-info',
                'POST /api/profile/education',
                'POST /api/profile/career',
                'POST /api/profile/physical',
                'POST /api/profile/faith',
                'POST /api/profile/photos',
                'POST /api/profile/update',
                'POST /api/profile/complete',
            ],
            'verification' => [
                'POST /api/verify-phone/send',
                'POST /api/verify-phone/resend',
                'POST /api/verify-phone/verify',
            ],
            'settings' => [
                'POST /api/change-password',
                'POST /api/logout',
            ],
            'lookup' => [
                'GET /api/countries',
                'GET /api/profile-options',
            ],
        ],
    ]);
});

// Lookup (public)
Route::get('/countries', [LookupController::class, 'countries']);
Route::get('/profile-options', [LookupController::class, 'profileOptions']);

// Public Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email-otp', [AuthController::class, 'verifyEmailOtp']);
Route::post('/resend-email-otp', [AuthController::class, 'resendEmailOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/social-login', [SocialAuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('/set-new-password', [AuthController::class, 'setNewPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::post('/verify-phone/send', [AuthController::class, 'sendPhoneOtp']);
    Route::post('/verify-phone/resend', [AuthController::class, 'resendPhoneOtp']);
    Route::post('/verify-phone/verify', [AuthController::class, 'verifyPhoneOtp']);

    Route::prefix('profile')->group(function () {
        Route::post('/country', [ProfileController::class, 'updateCountry']);
        Route::post('/basic-info', [ProfileController::class, 'updateBasicInfo']);
        Route::post('/education', [ProfileController::class, 'updateEducation']);
        Route::post('/career', [ProfileController::class, 'updateCareer']);
        Route::post('/physical', [ProfileController::class, 'updatePhysical']);
        Route::post('/faith', [ProfileController::class, 'updateFaith']);
        Route::post('/photos', [ProfileController::class, 'uploadPhotos']);
        Route::post('/update', [ProfileController::class, 'updateProfile']);
        Route::post('/complete', [ProfileController::class, 'completeProfile']);
    });

    Route::prefix('matches')->group(function () {
        Route::get('/home', [MatchController::class, 'home']);
        Route::get('/search', [MatchController::class, 'search']);
        Route::get('/filter', [MatchController::class, 'filter']);
        Route::get('/{user}', [MatchController::class, 'show']);
    });

    Route::get('/shortlist', [ShortlistController::class, 'index']);
    Route::post('/shortlist/{user}/interest', [ShortlistController::class, 'sendInterest']);
    Route::post('/shortlist/{user}/pass', [ShortlistController::class, 'pass']);

    // Subscriptions and Referrals
    Route::get('/subscriptions', [\App\Http\Controllers\Api\SubscriptionController::class, 'index']);
    Route::get('/referrals/stats', [\App\Http\Controllers\Api\ReferralController::class, 'stats']);
    Route::get('/referrals/history', [\App\Http\Controllers\Api\ReferralController::class, 'history']);

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::apiResource('subscriptions', \App\Http\Controllers\Api\Admin\SubscriptionController::class);
        Route::get('settings', [\App\Http\Controllers\Api\Admin\SettingController::class, 'index']);
        Route::post('settings', [\App\Http\Controllers\Api\Admin\SettingController::class, 'update']);
    });
});

Route::fallback(function (Request $request) {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found.',
        'path' => $request->path(),
        'method' => $request->method(),
    ], 404);
});
