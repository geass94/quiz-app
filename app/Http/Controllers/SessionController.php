<?php

namespace App\Http\Controllers;

use App\Data\QuestionData;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function start(Request $request)
    {
        $user = $request->user();
        $mode = $user->mode ?? Quiz::TYPES['BINARY'];

        $questions = Question::query()
            ->where('type', $mode)
            ->with('answers')
            ->inRandomOrder()
            ->limit(Quiz::SESSION_SIZE)
            ->get();

        $session = UserQuiz::create([
            'user_id' => $user->id,
            'mode' => $mode,
            'total_questions' => $questions->count(),
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Good Luck!',
            'data' => [
                'sessionId' => $session->id,
                'duration' => Quiz::SESSION_DURATION,
                'totalQuestions' => $questions->count(),
                'questions' => QuestionData::collection($questions),
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $session = UserQuiz::query()->findOrFail($request->input('sessionId'));

        if ($session->user_id !== $request->user()->id) {
            abort(403);
        }

        $answers = $request->input('answeredQuestions', []);
        $score = 0;
        foreach ($answers as $answer) {
            UserQuizAnswer::create([
                'user_quiz_id' => $session->id,
                'answer_id' => $answer['answerId'],
            ]);
            if (Answer::isCorrect($answer['answerId'])) {
                $score++;
            }
        }

        $timeSpent = (int) $request->input('timeSpent', 0);
        $session->update([
            'score' => $score,
            'time_left' => max(Quiz::SESSION_DURATION - $timeSpent, 0),
            'unanswered_count' => max($session->total_questions - count($answers), 0),
            'submitted_at' => now(),
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Submitted',
            'data' => [
                'score' => $session->score,
                'totalQuestions' => $session->total_questions,
                'unanswered' => $session->unanswered_count,
                'timeSpent' => $timeSpent,
            ],
        ]);
    }
}
