<?php

namespace App\Http\Controllers;

use App\Models\UserQuiz;

class LeaderboardController extends Controller
{
    public function index()
    {
        $best = UserQuiz::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('score')
            ->orderByDesc('time_left')
            ->get()
            ->unique('user_id')
            ->values();

        return view('leaderboard', [
            'rows' => $best,
        ]);
    }
}
