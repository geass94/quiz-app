<?php

namespace Database\Seeders;

use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@local.host',
            'password' => Hash::make('1234'),
            'is_admin' => true,
        ]);

        $pairs = [
            ['quote' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'author' => 'Lorem Ipsum'],
            ['quote' => 'Sed do eiusmod tempor incididunt ut labore et dolore.', 'author' => 'Dolor Sit'],
            ['quote' => 'Ut enim ad minim veniam, quis nostrud exercitation.', 'author' => 'Amet Consectetur'],
            ['quote' => 'Duis aute irure dolor in reprehenderit in voluptate.', 'author' => 'Adipiscing Elit'],
            ['quote' => 'Excepteur sint occaecat cupidatat non proident.', 'author' => 'Sed Eiusmod'],
            ['quote' => 'Curabitur pretium tincidunt lacus, nulla gravida orci.', 'author' => 'Tempor Incididunt'],
            ['quote' => 'Nullam varius, turpis et commodo pharetra, est eros.', 'author' => 'Magna Aliqua'],
            ['quote' => 'Praesent eu nulla at lectus convallis tristique non.', 'author' => 'Veniam Nostrud'],
            ['quote' => 'Vestibulum ante ipsum primis in faucibus orci luctus.', 'author' => 'Ullamco Laboris'],
            ['quote' => 'Suspendisse potenti morbi vehicula tellus eu velit.', 'author' => 'Aliquip Commodo'],
            ['quote' => 'Aenean tellus metus, bibendum sed, posuere ac, mattis non.', 'author' => 'Reprehenderit Voluptate'],
            ['quote' => 'Vivamus quis mi vestibulum laoreet ligula in vehicula.', 'author' => 'Cillum Dolore'],
        ];

        $distractors = [
            'Pariatur Excepteur', 'Cupidatat Sunt', 'Officia Deserunt',
            'Mollit Anim', 'Laborum Sed', 'Lacus Curabitur',
            'Pharetra Eros', 'Bibendum Orci', 'Tristique Convallis',
        ];

        $binaryQuiz = Quiz::create([
            'name' => 'Lorem Ipsum — Binary',
            'type' => Quiz::TYPES['BINARY'],
            'time' => Quiz::SESSION_DURATION,
        ]);

        foreach ($pairs as $pair) {
            $attributeCorrectly = random_int(0, 1) === 0;
            $attribution = $attributeCorrectly
                ? $pair['author']
                : $distractors[array_rand($distractors)];

            $isYesCorrect = $attribution === $pair['author'];

            $question = Question::create([
                'quiz_id' => $binaryQuiz->id,
                'type' => Quiz::TYPES['BINARY'],
                'content' => "Did {$attribution} say: \"{$pair['quote']}\"?",
            ]);

            Answer::create([
                'question_id' => $question->id,
                'content' => 'Yes',
                'is_correct' => $isYesCorrect,
            ]);
            Answer::create([
                'question_id' => $question->id,
                'content' => 'No',
                'is_correct' => ! $isYesCorrect,
            ]);
        }

        $multiQuiz = Quiz::create([
            'name' => 'Lorem Ipsum — Multi',
            'type' => Quiz::TYPES['MULTI'],
            'time' => Quiz::SESSION_DURATION,
        ]);

        foreach ($pairs as $pair) {
            $wrongPool = array_values(array_filter(
                $distractors,
                fn ($d) => $d !== $pair['author']
            ));
            shuffle($wrongPool);

            $options = [$pair['author'], $wrongPool[0], $wrongPool[1]];
            shuffle($options);

            $question = Question::create([
                'quiz_id' => $multiQuiz->id,
                'type' => Quiz::TYPES['MULTI'],
                'content' => "Who said: \"{$pair['quote']}\"?",
            ]);

            foreach ($options as $option) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $option,
                    'is_correct' => $option === $pair['author'],
                ]);
            }
        }
    }
}
