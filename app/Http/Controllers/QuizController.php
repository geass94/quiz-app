<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeaderboardResource;
use App\Http\Resources\QuizResource;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input('quizName');
        $type = $request->input('quizType');
        $questions = $request->input('questions');
        logger($questions);
        try {
            DB::beginTransaction();
            $quiz = Quiz::create([
                'name' => $name,
                'type' => $type,
                'time' => $request->input('duration', 300)
            ]);
            foreach ($questions as $question) {
                $q = Question::create([
                    'quiz_id' => $quiz->id,
                    'content' => $question['question']
                ]);
                if (!isset($question['answers'])) throw new \Exception('Answers are not provided');
                $answers = $question['answers'];
                foreach ($answers as $answer) {
                    Answer::create([
                        'question_id' => $q->id,
                        'is_correct' => $answer['isCorrect'],
                        'content' => $answer['answer']
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'error' => false,
                'message' => 'Quiz Saved!',
                'data' => $quiz
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function start(Request $request)
    {
        $userQuiz = UserQuiz::updateOrCreate(['quiz_id' => $request->input('quizId'), 'user_id' => $request->user()->id],[
            'quiz_id' => $request->input('quizId'),
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Good Luck!',
            'data' => $userQuiz
        ]);
    }


    public function submit(Request $request)
    {
        $answers = $request->input('answeredQuestions', []);
        $userQuiz = UserQuiz::query()->find($request->input('userQuizId'));
        $quiz = $userQuiz->quiz;
        $score = 0;
        foreach ($answers as $answer) {
            UserQuizAnswer::create([
                'user_quiz_id' => $request->input('userQuizId'),
                'answer_id' => $answer['answerId']
            ]);
            if (Answer::isCorrect($answer['answerId'])) $score++;
        }

        $userQuiz->update([
            'score' => $score,
            'time_left' => $quiz->time - $request->input('timeSpent', 0)
        ]);
        logger('Leaderboard', [
            'quizId' => $quiz->id,
        ]);
        $leaderboard = UserQuiz::query()
            ->where('quiz_id', '=', $quiz->id)
            ->orderByDesc('score')
            ->orderByDesc('time_left')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'Submitted',
            'data' => LeaderboardResource::collection($leaderboard)
        ]);
    }

    public function showOne(int $quizId)
    {
        $quiz = Quiz::query()->where('id', '=', $quizId)->with(['questions' => function($q) {
            return $q->with('answers');
        }])->first();
        return view('quiz')->with([
            'quiz' => QuizResource::make($quiz)->toJson()
        ]);
    }
}
