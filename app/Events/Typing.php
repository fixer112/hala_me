<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Typing implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $chat_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($chat_id,  $user_id)
    {
        $this->user_id = $user_id;
        $this->chat_id = $chat_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        try {
            broadcast(new UserOnline(User::find($this->user_id)))->toOthers();
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new PrivateChannel("chat.{$this->chat_id}");
    }
}