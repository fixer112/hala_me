<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Events\Typing;
use App\Models\Message;
use App\Events\ChatLoaded;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use App\Http\Resources\ChatResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $sender)
    {

        $chats = Auth::user()->chats->filter(fn ($chat) => $sender->chats->contains($chat));

        while ($chats->count() > 1) {
            $chats->last()->delete();
            $chats = Auth::user()->chats->filter(fn ($chat) => $sender->chats->contains($chat));
        }

        $chat = $chats->first();

        if (!$chat) {
            $chat = Chat::create();
            $chat->users()->sync([Auth::id(), $sender->id]);
        }

        $read = request()->read == '0' ? 0 : 1;

        $notify =
            request()->notify == '0' ? 0 : 1;

        //return $read;

        $data = ['delivered' => 1, 'read' => $read];

        //if (!request()->read || request()->read != 0) {
        //$data['read'] = 1;
        //}

        //return Auth::id();
        $chat->messages->whereNotIn('user_id', [Auth::id()])->sortByDesc('id')->filter(fn ($message) => $message->delivered == 0 || $message->read == 0)->each(fn ($message) => $message->update($data));
        //return $chat->messages->whereNotIn('user_id', [Auth::id()])->where('read', 0);
        //return $chat->messages->sortByDesc('id')->first();
        $data = new ChatResource($chat->load(['messages', 'users']));
        if ($notify == 1) {
            //return $notify;
            try {
                broadcast(new ChatLoaded($data))->toOthers();
            } catch (\Throwable $th) {
            }
        }
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $sender)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $sender)
    {
        $this->validate(request(), [
            //'reciever_id' => 'required|exists:messages',
            'body' => 'required|string',
            'type' => 'nullable|in:text',
            'uid' => 'nullable|string',
            'replied_id' => 'nullable|integer|exists:messages,id',
        ]);

        $chat = Auth::user()->chats->filter(fn ($chat) => $sender->chats->contains($chat))->first();

        if (!$chat) {
            $chat = Chat::create();
            $chat->users()->sync([Auth::id(), $sender->id]);
        }

        //$message = Message::find(1);
        $message = Message::where('uid', request()->uid)->first();
        if (!$message) {
            $message = $chat->messages()->create([
                'user_id' => Auth::id(),
                'body' => request()->body,
                'type' => request()->type ?? 'text',
                'uid' => request()->uid ?? Str::uuid(),
                'replied_id' => request()->replied_id ?? null,

            ]);
        }

        if (!in_array(($sender->id), $message->chat->users->pluck('id')->toArray())) {
            abort(422, 'Uid taken.');
        }
        $mr = new MessageResource($message);

        try {
            broadcast(new MessageCreated($mr))->toOthers();
        } catch (\Throwable $th) {
            //throw $th;
        }

        $res = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('FCM_KEY'),
            ]
        )->post('https://fcm.googleapis.com/fcm/send', [

            "registration_ids" => [$sender->fcm_token],
            /*  "notification" => [
                "title" => "FCM",
                "body" => "messaging tutorial"
            ], */
            "data" => $mr
        ]);
        //MessageCreated::dispatch(new MessageResource($message));

        return new MessageResource(Message::find($message->id)->load(['chat.users']));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

    }

    public function deleteMessages(array $ids = null)
    {
        $user = Auth::user();
        $uids = !is_null($ids) ? $ids : (request()->ids ?  json_decode(request()->ids, true) : []);
        //return $uids;
        $messages = $user->messages->whereIn('uid', $uids);

        $others = collect($uids)->diff($messages->pluck('uid')->toArray())->values();

        //return $others;


        Message::whereIn('uid', $others)->update(['hidden' => 1]);



        if ($messages->count() > 0) {
            $messages = $messages->toQuery();
            $messages->delete();
        }

        //return $messages;

        return true;
    }

    public function deleteChats(User $sender)
    {

        $chat = Auth::user()->chats->filter(fn ($chat) => $sender->chats->contains($chat))->first();

        if (!$chat) {
            $chat = Chat::create();
            $chat->users()->sync([Auth::id(), $sender->id]);
        }

        $ids = $chat->messages->pluck('uid')->toArray();
        return $this->deleteMessages($ids);
        return $ids;

        return true;
    }


    public function typing(User $sender)
    {

        $chat = Auth::user()->chats->filter(fn ($chat) => $sender->chats->contains($chat))->first();
        //return $chat->id;

        if (!$chat) {
            $chat = Chat::create();
            $chat->users()->sync([Auth::id(), $sender->id]);
        }

        try {
            broadcast(new Typing($chat->id, Auth::id()))->toOthers();
        } catch (\Throwable $th) {
        }
        return true;
    }

    function alertRecieved(Message $message)
    {
        $alerted = request()->alerted == '0' ? 0 : 1;
        $message->update(['alerted' => $alerted]);
        return $message;
    }
}