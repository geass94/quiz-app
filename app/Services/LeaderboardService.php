<?php

namespace App\Services;

use App\Models\UserQuiz;
use Illuminate\Support\Collection;

class LeaderboardService
{
    public function topScorers(): Collection
    {
        return UserQuiz::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('score')
            ->orderByDesc('time_left')
            ->get()
            ->unique('user_id')
            ->values();
    }
}
