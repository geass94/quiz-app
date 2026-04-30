<?php

namespace App\Data;

use App\Models\Quiz\Quiz;
use App\Models\UserQuiz;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class QuizData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public int $time,
        public bool $completed,
        #[DataCollectionOf(QuestionData::class)]
        public DataCollection $questions,
    ) {}

    public static function fromModel(Quiz $quiz): self
    {
        return new self(
            id: $quiz->id,
            name: $quiz->name,
            type: $quiz->type,
            time: $quiz->time,
            completed: UserQuiz::query()
                ->where('user_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->exists(),
            questions: QuestionData::collect($quiz->questions, DataCollection::class),
        );
    }
}
