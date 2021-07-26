<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = [
    'name',
    'email',
    'password',
    ]; */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_id',
        'fcm_token',
        'otp',
    ];

    protected $appends = ['image_url'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function chats()
    {
        return $this->belongsToMany(Chat::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    public function getImageUrlAttribute()
    {
        return 'avatar.png';
    }
}