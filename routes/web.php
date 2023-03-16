<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard')->with([
        'quizzes' => \App\Http\Resources\QuizResource::collection(\App\Models\Quiz\Quiz::all())->toJson()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/create-quiz', function () {
    return view('quiz-form');
})->middleware(['auth', 'verified'])->name('quiz-form');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/quiz/{quizId}', [\App\Http\Controllers\QuizController::class, 'showOne']);
});

Route::group(['prefix' => 'ajax', 'middleware' => ['auth']], function () {
   Route::group(['prefix' => 'quiz'], function () {
       Route::post('/', [\App\Http\Controllers\QuizController::class, 'create']);
       Route::post('/start', [\App\Http\Controllers\QuizController::class, 'start']);
       Route::post('/submit', [\App\Http\Controllers\QuizController::class, 'submit']);
   });
});

require __DIR__.'/auth.php';
