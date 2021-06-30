<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['users'] = UserResource::collection($this->whenLoaded('users'));
        $data['messages'] = MessageResource::collection($this->whenLoaded('messages', fn () => $this->messages->sortByDesc('id')->take(20)));
        return $data;
    }
}