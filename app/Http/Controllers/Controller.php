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

        $message = Message::find(2);
        $res = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer AAAAtdlHoJk:APA91bFiPCLhv9J7CPfuT70LAnGJKnj4qhRYZ3sKUmgasERT2klh-hPiiHnC3xVxeQ8BdU5R-GVGd-6bW2uBaXL0piJWFirjSHkqDw1JWjChncBMC1dZ3I_D5ChbZ8RRwb_gUM080utA'
            ]
        )->post('https://fcm.googleapis.com/fcm/send', [
            //"to" => "/topics/messaging",
            "registration_ids" => ["eDH7leOaQPenBvUqWGHen6:APA91bFLux82IiyPpRsE5q5Fc4FKLW6kcL222znz4ldIHAyuOTbKsrEdJArYpzZzDLtXlPQv2crXlnNQXg3H2BJm2AeiJBHdPrZA7WH8d1cFa5tnPM0zAJzTWAnVZTyNtpX1wGMnBDrk"],
            /* "notification" => [
                "title" =>$message->sen,
                "body" => "messaging tutorial"
            ], */
            "data" => new MessageResource($message)
        ])->throw();

        return $res->json();
        $user = User::find(1);
        //return Sms::sendSms('8106813749', 'test');
        return $user->image_url;
        Auth::login(User::find(2));

        broadcast(new UserOnline(User::find(2)))->toOthers();
        return;
        broadcast(new ChatLoaded(new ChatResource(Chat::find(request()->id ?? 1)->load(['messages', 'users']))))->toOthers();
        //broadcast(new MessageCreated(new MessageResource(Chat::find(1)->messages->first())))->toOthers();

    }
}