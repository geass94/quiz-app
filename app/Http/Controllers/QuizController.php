<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Quiz;
use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private QuizService $quizService) {}

    public function createForm()
    {
        return view('quiz-form');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'quizName' => 'required|string|max:255',
            'quizType' => 'required|in:'.implode(',', Quiz::TYPES),
            'duration' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.isCorrect' => 'required|boolean',
        ]);

        $quiz = $this->quizService->createQuiz($data);

        return response()->json([
            'error' => false,
            'message' => 'Quiz Saved!',
            'data' => $quiz,
        ]);
    }
}
