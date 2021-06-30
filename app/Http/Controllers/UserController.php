<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserOnline;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

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
        $numbers = json_decode(request()->numbers, true);
        return User::whereIn('phone_number', formatPhoneNumbers($numbers))->pluck('phone_number')->toArray();
    }
}