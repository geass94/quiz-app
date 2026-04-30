<?php

namespace App\Http\Controllers;

use App\Services\SessionService;

class AdminController extends Controller
{
    public function __construct(private SessionService $sessions) {}

    public function history()
    {
        return view('admin.history', [
            'sessions' => $this->sessions->history(),
        ]);
    }
}
