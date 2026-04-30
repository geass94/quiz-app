<?php

namespace App\Http\Controllers;

use App\Data\QuestionData;
use App\Models\Quiz\Quiz;
use App\Services\SessionService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(private SessionService $sessions) {}

    public function start(Request $request)
    {
        $request->validate([
            'questionIds' => 'nullable|array|max:'.Quiz::SESSION_SIZE,
            'questionIds.*' => 'integer',
        ]);

        $result = $this->sessions->startSession(
            $request->user(),
            $request->input('questionIds')
        );

        return response()->json([
            'error' => false,
            'message' => 'Good Luck!',
            'data' => [
                'sessionId' => $result['session']->id,
                'duration' => Quiz::SESSION_DURATION,
                'totalQuestions' => $result['questions']->count(),
                'questions' => QuestionData::collection($result['questions']),
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

        $result = $this->sessions->recordAnswer(
            $request->user(),
            (int) $request->input('sessionId'),
            (int) $request->input('questionId'),
            (int) $request->input('answerId'),
        );

        return response()->json([
            'error' => false,
            'data' => $result,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|integer',
            'timeSpent' => 'nullable|integer|min:0',
        ]);

        $session = $this->sessions->submitSession(
            $request->user(),
            (int) $request->input('sessionId'),
            (int) $request->input('timeSpent', 0),
        );

        return response()->json([
            'error' => false,
            'message' => 'Submitted',
            'data' => [
                'score' => $session->score,
                'totalQuestions' => $session->total_questions,
                'unanswered' => $session->unanswered_count,
                'timeSpent' => Quiz::SESSION_DURATION - (int) $session->time_left,
            ],
        ]);
    }
}
