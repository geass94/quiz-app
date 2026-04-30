<?php

namespace App\Data;

use App\Models\Quiz\Question;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class QuestionData extends Data
{
    public function __construct(
        public int $id,
        public string $question,
        #[DataCollectionOf(AnswerData::class)]
        public DataCollection $answers,
    ) {}

    public static function fromModel(Question $question): self
    {
        return new self(
            id: $question->id,
            question: $question->content,
            answers: AnswerData::collect($question->answers, DataCollection::class),
        );
    }
}
