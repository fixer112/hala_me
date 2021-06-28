<?php

namespace App\Http\Controllers;

use App\Events\ChatLoaded;
use App\Events\UserOnline;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
        Auth::login(User::find(2));

        broadcast(new UserOnline(User::find(2)))->toOthers();
        return;
        broadcast(new ChatLoaded(new ChatResource(Chat::find(request()->id ?? 1)->load(['messages', 'users']))))->toOthers();
        //broadcast(new MessageCreated(new MessageResource(Chat::find(1)->messages->first())))->toOthers();

    }
}