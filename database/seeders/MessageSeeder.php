<?php

namespace Database\Seeders;

use App\Models\Chat;
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
        $chat = Chat::create([]);

        $chat->users()->sync([1, 2]);

        $chat->messages()->create([
            "user_id" => 1,
            "body" => "Hello",
        ]);

        $chat->messages()->create([
            "user_id" => 2,
            "body" => "Hi, how are you?",
        ]);

        $chat->messages()->create([
            "user_id" => 1,
            "body" => "Am doing fine! Wbu?",
        ]);

        $chat->messages()->create([
            "user_id" => 2,
            "body" => "Am good too. Nice meeting you",
        ]);

    }
}