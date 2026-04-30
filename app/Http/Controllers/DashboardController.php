<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Quiz;
use App\Services\SessionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private SessionService $sessions) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return view('dashboard', [
            'mode' => $user->mode ?? Quiz::TYPES['BINARY'],
            'duration' => Quiz::SESSION_DURATION,
            'resume' => $this->sessions->resumeSession($user),
        ]);
    }
}
