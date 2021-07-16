<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        $data['messages'] = MessageResource::collection($this->whenLoaded('messages', fn () => $this->messages->sortByDesc('id')->take(20)->filter(function (Message $msg) {
            if ($msg->user_id != Auth::id()) {
                return $msg->hidden == 0;
            }
            return $msg;
        })));
        return $data;
    }
}