<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserQuiz;

class SettingsService
{
    public function changeMode(User $user, string $newMode): bool
    {
        if ($user->mode === $newMode) {
            return false;
        }

        UserQuiz::query()
            ->where('user_id', $user->id)
            ->whereNull('time_left')
            ->delete();

        $user->update(['mode' => $newMode]);

        return true;
    }
}
