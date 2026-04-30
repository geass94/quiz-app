<?php

namespace App\Http\Controllers;

use App\Data\QuizData;
use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;
use Spatie\LaravelData\DataCollection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->user()->mode ?? Quiz::TYPES['BINARY'];

        return view('dashboard', [
            'quizzes' => QuizData::collection(
                Quiz::query()->where('type', $mode)->get(),
            )->toJson(),
        ]);
    }
}
