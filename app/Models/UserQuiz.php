<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function answers()
    {
        return $this->hasMany(UserQuizAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
