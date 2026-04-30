<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/create-quiz', [QuizController::class, 'createForm'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('quiz-form');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::group(['prefix' => 'ajax', 'middleware' => ['auth']], function () {
    Route::post('/quiz', [QuizController::class, 'create'])->middleware('admin');

    Route::group(['prefix' => 'session'], function () {
        Route::post('/start', [SessionController::class, 'start']);
        Route::post('/answer', [SessionController::class, 'answer']);
        Route::post('/submit', [SessionController::class, 'submit']);
    });
});

require __DIR__.'/auth.php';
