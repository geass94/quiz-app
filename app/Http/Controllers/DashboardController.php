<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $request->user()->mode ?? Quiz::TYPES['BINARY'];

        return view('dashboard', [
            'quizzes' => QuizResource::collection(
                Quiz::query()->where('type', $mode)->get()
            )->toJson(),
        ]);
    }
}
