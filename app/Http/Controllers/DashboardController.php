<?php

namespace App\Http\Controllers;

use App\Data\QuestionData;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $mode = $user->mode ?? Quiz::TYPES['BINARY'];

        return view('dashboard', [
            'mode' => $mode,
            'duration' => Quiz::SESSION_DURATION,
            'resume' => $this->buildResumePayload($user),
        ]);
    }

    private function buildResumePayload(User $user): ?array
    {
        $session = UserQuiz::query()
            ->where('user_id', $user->id)
            ->whereNull('submitted_at')
            ->latest('id')
            ->first();

        if (! $session) {
            return null;
        }

        $elapsed = now()->getTimestamp() - $session->created_at->getTimestamp();
        $timeLeft = Quiz::SESSION_DURATION - $elapsed;

        if ($timeLeft <= 0 || empty($session->question_ids)) {
            $session->finalize($elapsed);

            return [
                'status' => 'completed',
                'score' => $session->score,
                'totalQuestions' => $session->total_questions,
                'unanswered' => $session->unanswered_count,
                'timeSpent' => Quiz::SESSION_DURATION - $session->time_left,
                'previousQuestionIds' => $session->question_ids ?? [],
            ];
        }

        $fetched = Question::query()
            ->whereIn('id', $session->question_ids)
            ->where('type', $session->mode)
            ->with('answers')
            ->get()
            ->keyBy('id');

        $questions = collect($session->question_ids)
            ->map(fn ($id) => $fetched->get($id))
            ->filter()
            ->values();

        $answered = UserQuizAnswer::query()
            ->with('answer.question.answers')
            ->where('user_quiz_id', $session->id)
            ->get()
            ->map(function ($a) {
                $correct = $a->answer?->question?->answers->firstWhere('is_correct', true);

                return [
                    'questionId' => $a->answer?->question_id,
                    'answerId' => $a->answer_id,
                    'isCorrect' => (bool) ($a->answer?->is_correct),
                    'correctAnswer' => $correct?->content,
                ];
            })
            ->filter(fn ($x) => $x['questionId'] !== null)
            ->values();

        return [
            'status' => 'resume',
            'sessionId' => $session->id,
            'duration' => Quiz::SESSION_DURATION,
            'timeLeft' => $timeLeft,
            'totalQuestions' => count($session->question_ids),
            'questions' => QuestionData::collection($questions),
            'answered' => $answered,
        ];
    }
}
