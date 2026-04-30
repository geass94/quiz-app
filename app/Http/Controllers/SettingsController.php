<?php

namespace App\Http\Controllers;

use App\Models\Quiz\Quiz;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settings) {}

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
            'mode' => ['required', 'in:'.implode(',', Quiz::TYPES)],
        ]);

        $this->settings->changeMode($request->user(), $request->input('mode'));

        return redirect()
            ->route('settings.edit')
            ->with('status', 'mode-updated');
    }
}
