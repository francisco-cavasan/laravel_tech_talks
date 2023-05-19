<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'starts_at' => $this->email,
            'ends_at' => $this->created_at,
            'is_finished' => $this->is_finished,
            'users_count' => $this->users_count,
            'users' => $this->whenLoaded('users', function () {
                return UserResource::collection($this->users);
            })
        ];
    }
}
