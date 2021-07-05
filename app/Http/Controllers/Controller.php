<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Events\ChatLoaded;
use App\Events\UserOnline;
use Illuminate\Support\Str;
use App\Http\Resources\ChatResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
        $user = User::find(1);
        return $user->image_url;
        Auth::login(User::find(2));

        broadcast(new UserOnline(User::find(2)))->toOthers();
        return;
        broadcast(new ChatLoaded(new ChatResource(Chat::find(request()->id ?? 1)->load(['messages', 'users']))))->toOthers();
        //broadcast(new MessageCreated(new MessageResource(Chat::find(1)->messages->first())))->toOthers();

    }
}