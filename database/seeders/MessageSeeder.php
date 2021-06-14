<?php

namespace Database\Seeders;

use App\Models\Chat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            "uid" => Str::uuid(),
        ]);

        $chat->messages()->create([
            "user_id" => 2,
            "body" => "Hi, how are you?",
            "uid" => Str::uuid(),
        ]);

        $chat->messages()->create([
            "user_id" => 1,
            "body" => "Am doing fine! Wbu?",
            "uid" => Str::uuid(),
        ]);

        $chat->messages()->create([
            "user_id" => 2,
            "body" => "Am good too. Nice meeting you",
            "uid" => Str::uuid(),
        ]);

        $chat = Chat::create([]);
        $chat->users()->sync([1, 3]);

        $chat = Chat::create([]);
        $chat->users()->sync([1, 4]);

    }
}