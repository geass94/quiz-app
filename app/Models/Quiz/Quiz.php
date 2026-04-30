<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    const SESSION_SIZE = 10;

    const SESSION_DURATION = 300;

    const TYPES = [
        'BINARY' => 'BINARY',
        'MULTI' => 'MULTI',
    ];

    protected $fillable = [
        'name',
        'type',
        'time',
    ];

    public function setCompletedAttribute($value = false)
    {
        $this->attributes['completed'] = $value;
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasManyThrough(Answer::class, Question::class, '', '', '', '');
    }
}
