<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function createForm()
    {
        return view('quiz-form');
    }

    public function create(Request $request)
    {
        $request->validate([
            'quizName' => 'required|string|max:255',
            'quizType' => 'required|in:'.implode(',', Quiz::TYPES),
            'duration' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.isCorrect' => 'required|boolean',
        ]);

        $type = $request->input('quizType');
        $questions = $request->input('questions');

        foreach ($questions as $i => $q) {
            $count = count($q['answers']);
            $correctCount = 0;
            foreach ($q['answers'] as $a) {
                if ($a['isCorrect']) {
                    $correctCount++;
                }
            }

            if ($correctCount !== 1) {
                return response()->json([
                    'error' => true,
                    'message' => 'Question '.($i + 1).' must have exactly one correct answer.',
                ], 422);
            }

            if ($type === Quiz::TYPES['BINARY'] && $count !== 2) {
                return response()->json([
                    'error' => true,
                    'message' => 'Binary question '.($i + 1).' must have exactly 2 answers.',
                ], 422);
            }

            if ($type === Quiz::TYPES['MULTI'] && ($count < 2 || $count > 3)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Multi-choice question '.($i + 1).' must have 2 or 3 answers.',
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $quiz = Quiz::create([
                'name' => $request->input('quizName'),
                'type' => $type,
                'time' => $request->input('duration', Quiz::SESSION_DURATION),
            ]);

            foreach ($questions as $question) {
                $q = Question::create([
                    'quiz_id' => $quiz->id,
                    'type' => $type,
                    'content' => $question['question'],
                ]);

                foreach ($question['answers'] as $answer) {
                    Answer::create([
                        'question_id' => $q->id,
                        'is_correct' => $answer['isCorrect'],
                        'content' => $answer['answer'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Quiz Saved!',
                'data' => $quiz,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
