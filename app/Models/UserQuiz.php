<?php

namespace App\Models;

use App\Models\Quiz\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mode',
        'score',
        'time_left',
        'total_questions',
        'unanswered_count',
        'submitted_at',
        'question_ids',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'question_ids' => 'array',
    ];

    public function answers()
    {
        return $this->hasMany(UserQuizAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finalize(?int $timeSpentSeconds = null): void
    {
        $answers = $this->answers()->with('answer')->get();
        $score = $answers->filter(fn ($a) => $a->answer && $a->answer->is_correct)->count();
        $answeredCount = $answers->count();

        $timeSpent = $timeSpentSeconds ?? Quiz::SESSION_DURATION;

        $this->update([
            'score' => $score,
            'time_left' => max(Quiz::SESSION_DURATION - $timeSpent, 0),
            'unanswered_count' => max($this->total_questions - $answeredCount, 0),
            'submitted_at' => now(),
        ]);
    }
}
