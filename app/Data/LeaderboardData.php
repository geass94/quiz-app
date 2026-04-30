<?php

namespace App\Data;

use App\Models\UserQuiz;
use Spatie\LaravelData\Data;

class LeaderboardData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public int $score,
        public int $timeSpent,
    ) {}

    public static function fromModel(UserQuiz $userQuiz): self
    {
        return new self(
            name: $userQuiz->user->name,
            email: $userQuiz->user->email,
            score: $userQuiz->score,
            timeSpent: $userQuiz->quiz->time - $userQuiz->time_left,
        );
    }
}
