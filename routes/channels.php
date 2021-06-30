<?php

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chat}', function ($user, Chat $chat) {
    return  Auth::check();
    //return (int) $user->id === optional($message)->user_id;
});

Broadcast::channel('user.{user}', function ($user, User $u) {
    return Auth::check();
    //return (int) $user->id === optional($message)->user_id;
});