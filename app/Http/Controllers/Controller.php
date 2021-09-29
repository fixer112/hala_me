<?php

namespace App\Http\Controllers;


use App\Traits\Sms;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use App\Events\ChatLoaded;
use App\Events\UserOnline;
use Illuminate\Support\Str;
use App\Http\Resources\ChatResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\MessageResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test()
    {
        //return new MessageResource(Message::find(17)->load(['chat.users']));
    }
}