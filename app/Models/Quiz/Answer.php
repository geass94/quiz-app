<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'is_correct',
        'content',
    ];

    public static function isCorrect(int $id): bool
    {
        return Answer::query()->find($id)->is_correct;
    }
}
