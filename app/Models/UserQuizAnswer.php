<?php

namespace App\Models;

use App\Models\Quiz\Answer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_quiz_id',
        'answer_id',
    ];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
