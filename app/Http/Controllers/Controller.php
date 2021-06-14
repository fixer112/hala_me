<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Http\Resources\MessageResource;
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
        Auth::login(User::find(1));
        MessageCreated::dispatch(new MessageResource(Chat::find(request()->id ?? 1)->messages->first()));
        //broadcast(new MessageCreated(new MessageResource(Chat::find(1)->messages->first())))->toOthers();

    }
}