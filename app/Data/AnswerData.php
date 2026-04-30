<?php

namespace App\Data;

use App\Models\Quiz\Answer;
use Spatie\LaravelData\Data;

class AnswerData extends Data
{
    public function __construct(
        public int $id,
        public string $answer,
    ) {}

    public static function fromModel(Answer $answer): self
    {
        return new self(
            id: $answer->id,
            answer: $answer->content,
        );
    }
}
