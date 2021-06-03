<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::create([
            "sender_id" => 1,
            "reciever_id" => 2,
            "body" => "Hello",
        ]);

        Message::create([
            "sender_id" => 2,
            "reciever_id" => 1,
            "body" => "Hi, how are you?",
        ]);

        Message::create([
            "sender_id" => 1,
            "reciever_id" => 2,
            "body" => "Am doing fine! Wbu?",
        ]);

        Message::create([
            "sender_id" => 2,
            "reciever_id" => 1,
            "body" => "Am good too. Nice meeting you",
        ]);

    }
}