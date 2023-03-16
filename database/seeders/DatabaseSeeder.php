<?php

namespace Database\Seeders;

use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'admin',
            'email' => 'admin@local.host',
            'password' => Hash::make('1234'),
            'is_admin' => true
        ]);
        for ($i = 0; $i < 10; $i++) {
            $quiz = Quiz::create([
                'name' => "Quiz $i",
                'type' => Quiz::TYPES[$i % 2 == 0 ? 'BINARY' : 'MULTI'],
                'time' => 10,
            ]);
            $answerCount = $quiz->type === Quiz::TYPES['BINARY'] ? 2 : 3;
            for ($qi = 0; $qi < 10; $qi++) {
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'content' => "This is question $qi"
                ]);
                if ($answerCount === 2) {
                    Answer::create([
                        'question_id' => $question->id,
                        'content' => "Yes",
                        'is_correct' => false
                    ]);
                    Answer::create([
                        'question_id' => $question->id,
                        'content' => "No",
                        'is_correct' => true
                    ]);
                } else {
                    for ($ai = 0; $ai < $answerCount; $ai++) {
                        Answer::create([
                            'question_id' => $question->id,
                            'content' => "Answer $ai for Question $qi",
                            'is_correct' => $i % 2 === 0
                        ]);
                    }
                }
            }
        }
    }
}
