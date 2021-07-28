<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
        $data['chat'] = new ChatResource($this->chat);
        $data['sender'] = new UserResource($this->sender);
        $data['replied'] = $this->repliedMessage ? $this->repliedMessage->load(['sender', 'chat.users']) : null; //$this->whenLoaded('repliedMessage');
        //$data['replied'] = $this->replied;

        return $data;
    }
}