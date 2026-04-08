<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\UserSurveyResponseController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\ContestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('terms-conditions');
})->name('terms-conditions');

Route::get('/faqs', function () {
    return view('faqs');
})->name('faqs');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/survey-data', [DashboardController::class, 'surveyData'])->name('admin.dashboard.survey-data');

        Route::resource('users', UserController::class, ['names' => 'admin.users']);
        Route::post('users/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

        Route::resource('games', GameController::class, ['names' => 'admin.games']);
        Route::post('games/toggle-status', [GameController::class, 'toggleStatus'])->name('admin.games.toggle-status');
        Route::resource('surveys', SurveyController::class, ['names' => 'admin.surveys']);
        Route::post('surveys/toggle-status', [SurveyController::class, 'toggleStatus'])->name('admin.surveys.toggle-status');
        Route::post('surveys/toggle-store-answers', [SurveyController::class, 'toggleStoreAnswers'])->name('admin.surveys.toggle-store-answers');
        Route::resource('user-surveys', UserSurveyResponseController::class, ['names' => 'admin.user-surveys'])->only(['index', 'show']);
        Route::get('game-categories', [GameController::class, 'categoryIndex'])->name('admin.game-categories.index');
        Route::post('game-categories', [GameController::class, 'categoryStore'])->name('admin.game-categories.store');
        Route::put('game-categories/{id}', [GameController::class, 'categoryUpdate'])->name('admin.game-categories.update');
        Route::delete('game-categories/{id}', [GameController::class, 'categoryDestroy'])->name('admin.game-categories.destroy');
        Route::post('game-categories/toggle-status', [GameController::class, 'categoryToggleStatus'])->name('admin.game-categories.toggle-status');
 
        Route::get('withdrawals', [WithdrawalController::class, 'index'])->name('admin.withdrawals.index');
        Route::resource('contests', ContestController::class, ['names' => 'admin.contests']);
        Route::post('contests/toggle-status', [ContestController::class, 'toggleStatus'])->name('admin.contests.toggle-status');
    });
});