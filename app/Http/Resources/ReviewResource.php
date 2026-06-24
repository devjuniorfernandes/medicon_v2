<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user_name' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }, 'Utilizador Anónimo'),
            'user_avatar' => $this->whenLoaded('user', function () {
                return $this->user->avatar ? asset('storage/' . $this->user->avatar) : null;
            }),
            'hospital_response' => $this->hospital_response,
            'responded_at' => $this->responded_at ? $this->responded_at->toIso8601String() : null,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
