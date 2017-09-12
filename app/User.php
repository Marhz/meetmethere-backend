<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = ['avatar'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute()
    {
        return "https://www.gravatar.com/avatar/" . md5($this->email) . "?d=retro&s=64";
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
