<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $this->user;
        $quiz = $this->quiz;
        return [
            'name' => $user->name,
            'email' => $user->email,
            'score' => $this->score,
            'timeSpent' => $quiz->time - $this->time_left
        ];
    }
}
