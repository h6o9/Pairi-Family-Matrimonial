<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MarriageBureau\AuthController;

Route::name('marriage-bureau.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('marriage-bureau.login');
    });

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:marriage_bureau')->group(function () {
        Route::get('subscription', [\App\Http\Controllers\MarriageBureau\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('subscription', [\App\Http\Controllers\MarriageBureau\SubscriptionController::class, 'store'])->name('subscription.store');
        Route::post('subscription/upload-screenshot', [\App\Http\Controllers\MarriageBureau\SubscriptionController::class, 'uploadScreenshot'])->name('subscription.upload-screenshot');

        Route::middleware([\App\Http\Middleware\RequireMBSuspcription::class])->group(function() {
            Route::get('dashboard', function () {
                return view('marriage_bureau.dashboard');
            })->name('dashboard');

            Route::resource('users', \App\Http\Controllers\MarriageBureau\UserController::class);
        });

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
