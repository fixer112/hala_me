<?php

namespace App\Models;

use App\Models\Message as ModelsMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function repliedMessage()
    {
        return $this->hasOne(Message::class, 'id', 'replied_id');
    }
}