<?php

namespace App\Services;

use App\Models\UserQuiz;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaderboardService
{
    public function topScorers(int $perPage = 20): LengthAwarePaginator
    {
        $best = UserQuiz::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('score')
            ->orderByDesc('time_left')
            ->get()
            ->unique('user_id')
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage('page');

        return new LengthAwarePaginator(
            $best->forPage($page, $perPage)->values(),
            $best->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }
}
