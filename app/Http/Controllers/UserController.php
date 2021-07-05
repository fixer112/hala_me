<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Events\UserOnline;
use App\Events\MessageCreated;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        //return UserOnline::dispatch($user);
        try {
            broadcast(new UserOnline($user))->toOthers();
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($user->chats->count() > 0) {
            $messages =  $user->chats->toQuery()->join('messages', 'messages.chat_id', 'chats.id')->where('messages.alerted', 0)->get();
            $messages->each(function ($message) {
                try {
                    broadcast(new MessageCreated(new MessageResource($message)))->toOthers();
                } catch (\Throwable $th) {
                }
            });
        }

        return $data = new UserResource($user->load('chats.users', 'chats.messages'));
    }

    public function setOnline()
    {
        $this->validate(request(), [
            'status' => 'required|boolean',
        ]);

        $status = (int) request()->status;

        try {
            broadcast(new UserOnline(Auth::user(), $status))->toOthers();
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
        return 1;
        //return Auth::user();
    }

    public function checkNumbers()
    {
        //return request()->numbers;
        $numbers = json_decode(request()->numbers, true);
        return User::whereIn('phone_number', formatPhoneNumbers($numbers))->pluck('id', 'phone_number')->toArray();
    }
}