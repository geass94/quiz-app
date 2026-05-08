<?php

namespace App\Services;

use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function createQuiz(array $data): Quiz
    {
        $type = $data['quizType'];
        $questions = $data['questions'];

        $this->questionsAreFormedCorrectly($type, $questions);

        return DB::transaction(function () use ($data, $type, $questions) {
            $quiz = Quiz::create([
                'name' => $data['quizName'],
                'type' => $type,
                'time' => $data['duration'] ?? Quiz::SESSION_DURATION,
            ]);

            foreach ($questions as $question) {
                $row = Question::create([
                    'quiz_id' => $quiz->id,
                    'type' => $type,
                    'content' => $question['question'],
                ]);

                foreach ($question['answers'] as $answer) {
                    Answer::create([
                        'question_id' => $row->id,
                        'is_correct' => $answer['isCorrect'],
                        'content' => $answer['answer'],
                    ]);
                }
            }

            return $quiz;
        });
    }

    private function questionsAreFormedCorrectly(string $type, array $questions): void
    {
        foreach ($questions as $i => $q) {
            $count = count($q['answers']);
            $correctCount = 0;
            foreach ($q['answers'] as $a) {
                if ($a['isCorrect']) {
                    $correctCount++;
                }
            }

            if ($correctCount !== 1) {
                abort(422, 'Question '.($i + 1).' must have exactly one correct answer.');
            }
            if ($type === Quiz::TYPES['BINARY'] && $count !== 2) {
                abort(422, 'Binary question '.($i + 1).' must have exactly 2 answers.');
            }
            if ($type === Quiz::TYPES['MULTI'] && ($count < 2 || $count > 3)) {
                abort(422, 'Multi-choice question '.($i + 1).' must have 2 or 3 answers.');
            }
        }
    }
}
