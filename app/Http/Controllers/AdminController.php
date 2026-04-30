<?php

namespace App\Http\Controllers;

use App\Models\UserQuiz;

class AdminController extends Controller
{
    public function history()
    {
        $sessions = UserQuiz::query()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return view('admin.history', [
            'sessions' => $sessions,
        ]);
    }
}
