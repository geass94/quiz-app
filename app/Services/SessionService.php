<?php

namespace App\Services;

use App\Data\QuestionData;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SessionService
{
    /**
     * @return array{session: UserQuiz, questions: Collection}
     */
    public function startSession(User $user, ?array $providedQuestionIds = null): array
    {
        $mode = $user->mode ?? Quiz::TYPES['BINARY'];
        $questions = collect();

        if (! empty($providedQuestionIds)) {
            $fetched = Question::query()
                ->whereIn('id', $providedQuestionIds)
                ->where('type', $mode)
                ->with('answers')
                ->get()
                ->keyBy('id');

            $questions = collect($providedQuestionIds)
                ->map(fn ($id) => $fetched->get($id))
                ->filter()
                ->values();
        }

        if ($questions->isEmpty()) {
            $questions = Question::query()
                ->where('type', $mode)
                ->with('answers')
                ->inRandomOrder()
                ->limit(Quiz::SESSION_SIZE)
                ->get();
        }

        $session = UserQuiz::create([
            'user_id' => $user->id,
            'mode' => $mode,
            'total_questions' => $questions->count(),
            'question_ids' => $questions->pluck('id')->all(),
        ]);

        return [
            'session' => $session,
            'questions' => $questions,
        ];
    }

    public function recordAnswer(User $user, int $sessionId, int $questionId, int $answerId): array
    {
        $session = $this->retrieveActiveSession($user, $sessionId);

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

        return [
            'correct' => (bool) $answer->is_correct,
            'correctAnswer' => $correctAnswer?->content,
        ];
    }

    public function submitSession(User $user, int $sessionId, int $timeSpent): UserQuiz
    {
        $session = $this->retrieveActiveSession($user, $sessionId);
        $session->finalize($timeSpent);

        return $session->fresh();
    }

    public function resumeSession(User $user): ?array
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

    public function history(int $perPage = 20): LengthAwarePaginator
    {
        return UserQuiz::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')
            ->paginate($perPage);
    }

    private function retrieveActiveSession(User $user, int $sessionId): UserQuiz
    {
        $session = UserQuiz::query()->find($sessionId);
        if (! $session) {
            abort(404, 'Session not found');
        }
        if ($session->user_id !== $user->id) {
            abort(403);
        }
        if ($session->submitted_at !== null) {
            abort(409, 'Session already submitted');
        }

        return $session;
    }
}
