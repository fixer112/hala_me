<?php

namespace App\Http\Controllers;

use App\Events\UserOnline;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        //return UserOnline::dispatch($user);
        broadcast(new UserOnline($user))->toOthers();
        return $data = new UserResource($user->load('chats.users', 'chats.messages'));

    }

    public function setOnline()
    {
        $this->validate(request(), [
            'status' => 'required|boolean',
        ]);

        $status = (int) request()->status;

        broadcast(new UserOnline(Auth::user(), $status))->toOthers();

        return Auth::user();
    }
}