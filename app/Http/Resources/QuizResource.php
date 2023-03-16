<?php

namespace App\Http\Resources;

use App\Models\UserQuiz;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'time' => $this->time,
            'completed' => UserQuiz::query()->where('user_id', '=', Auth::id())->where('quiz_id', '=', $this->id)->exists(),
            'questions' => QuestionResource::collection($this->questions)
        ];
    }
}
