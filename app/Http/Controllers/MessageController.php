<?php

namespace App\Http\Controllers;

use App\Events\ChatLoaded;
use App\Events\MessageCreated;
use App\Events\UserOnline;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $sender)
    {

        $chat = Auth::user()->chats->filter(fn($chat) => $sender->chats->contains($chat))->first();

        if (!$chat) {
            $chat = Chat::create();
            $chat->users()->sync([Auth::id(), $sender->id]);
        }

        $data = ['delivered' => 1];

        if (!request()->read || request()->read != 0) {
            $data['read'] = 1;
        }

        if (!request()->online || request()->online == 1) {
            try {
                //code...
                broadcast(new UserOnline(Auth::user()))->toOthers();
            } catch (\Throwable$th) {
                //throw $th;
            }
        }

        $chat->messages->whereNotIn('user_id', [Auth::id()])->sortByDesc('id')->filter(fn($message) => $message->delivered == 0 || $message->read == 0)->take(10)->each(fn($message) => $message->update($data));

        $data = new ChatResource($chat->load(['messages', 'users']));
        try {
            broadcast(new ChatLoaded($data))->toOthers();
        } catch (\Throwable$th) {

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
            'uid' => 'nullable|string|unique:messages',
        ]);

        $chat = Auth::user()->chats->filter(fn($chat) => $sender->chats->contains($chat))->first();

        if (!$chat) {
            $chat = Chat::create();
            $chat->users()->sync([Auth::id(), $sender->id]);
        }

        //$message = Message::find(1);

        $message = $chat->messages()->create([
            'user_id' => Auth::id(),
            'body' => request()->body,
            'type' => request()->type ?? 'text',
            'uid' => request()->uid ?? Str::uuid(),

        ]);

        broadcast(new MessageCreated(new MessageResource($message)))->toOthers();
        //MessageCreated::dispatch(new MessageResource($message));

        return new MessageResource(Message::find($message->id));

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
}