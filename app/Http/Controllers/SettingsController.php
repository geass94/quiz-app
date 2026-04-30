<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Quiz;
use App\Models\UserQuiz;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        return view('settings', [
            'mode' => $request->user()->mode ?? Quiz::TYPES['BINARY'],
            'modes' => Quiz::TYPES,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'mode' => ['required', 'in:' . implode(',', Quiz::TYPES)],
        ]);

        $user = $request->user();
        $newMode = $request->input('mode');

        if ($user->mode !== $newMode) {
            UserQuiz::query()
                ->where('user_id', $user->id)
                ->whereNull('time_left')
                ->delete();

            $user->update(['mode' => $newMode]);
        }

        return redirect()
            ->route('settings.edit')
            ->with('status', 'mode-updated');
    }
}
