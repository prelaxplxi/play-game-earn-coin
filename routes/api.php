<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\ContestController;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\TrackController;

Route::post('/login', [UserAuthController::class, 'login'])->name('login');
Route::post('/register/normal', [UserAuthController::class, 'registerNormal']);
Route::post('/register/gmail', [UserAuthController::class, 'registerGmail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserAuthController::class, 'me']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::post('/change-password', [UserAuthController::class, 'changePassword']);
    Route::post('/update-profile', [UserAuthController::class, 'updateProfile']);

    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/category-wise', [GameController::class, 'categoryWiseList']);
    Route::post('/earn-coin', [GameController::class, 'storeEarnCoin']);
    Route::post('/withdraw', [GameController::class, 'withdraw']);
    Route::get('/withdraw-history', [GameController::class, 'withdrawHistory']);
    Route::get('/earning-history', [GameController::class, 'earningHistory']);
    Route::get('/contests', [ContestController::class, 'index']);
    Route::get('/surveys', [SurveyController::class, 'index']);
    Route::post('/surveys/submit', [SurveyController::class, 'storeResponse']);

    Route::prefix('track')->group(function () {
        Route::post('/click', [TrackController::class, 'trackClick']);
        Route::post('/post-back-event', [TrackController::class, 'trackEvent']);
    });
});