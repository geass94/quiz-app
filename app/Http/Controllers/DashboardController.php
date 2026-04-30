<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard', [
            'mode' => $request->user()->mode ?? Quiz::TYPES['BINARY'],
            'duration' => Quiz::SESSION_DURATION,
        ]);
    }
}
