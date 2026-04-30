<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;

class LeaderboardController extends Controller
{
    public function __construct(private LeaderboardService $leaderboard) {}

    public function index()
    {
        return view('leaderboard', [
            'rows' => $this->leaderboard->topScorers(),
        ]);
    }
}
