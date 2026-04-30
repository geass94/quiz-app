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

    public function answer(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|integer',
            'questionId' => 'required|integer',
            'answerId' => 'required|integer',
        ]);

        $session = UserQuiz::query()->findOrFail($request->input('sessionId'));

        if ($session->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($session->submitted_at !== null) {
            abort(409, 'Session already submitted');
        }

        $questionId = (int) $request->input('questionId');
        $answerId = (int) $request->input('answerId');

        $answer = Answer::query()->find($answerId);
        if (! $answer || $answer->question_id !== $questionId) {
            abort(422, 'Invalid answer');
        }

        $alreadyAnswered = UserQuizAnswer::query()
            ->where('user_quiz_id', $session->id)
            ->whereHas('answer', fn ($q) => $q->where('question_id', $questionId))
            ->exists();

        if (! $alreadyAnswered) {
            UserQuizAnswer::create([
                'user_quiz_id' => $session->id,
                'answer_id' => $answerId,
            ]);
        }

        $correctAnswer = Answer::query()
            ->where('question_id', $questionId)
            ->where('is_correct', true)
            ->first();

        return response()->json([
            'error' => false,
            'data' => [
                'correct' => (bool) $answer->is_correct,
                'correctAnswer' => $correctAnswer?->content,
            ],
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|integer',
            'timeSpent' => 'nullable|integer|min:0',
        ]);

        $session = UserQuiz::query()->findOrFail($request->input('sessionId'));

        if ($session->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($session->submitted_at !== null) {
            abort(409, 'Session already submitted');
        }

        $answers = UserQuizAnswer::query()
            ->with('answer')
            ->where('user_quiz_id', $session->id)
            ->get();

        $score = $answers->filter(fn ($a) => $a->answer && $a->answer->is_correct)->count();
        $answeredCount = $answers->count();

        $timeSpent = (int) $request->input('timeSpent', 0);
        $session->update([
            'score' => $score,
            'time_left' => max(Quiz::SESSION_DURATION - $timeSpent, 0),
            'unanswered_count' => max($session->total_questions - $answeredCount, 0),
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
