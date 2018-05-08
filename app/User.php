<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Client;
use App\Models\Partner;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'image', 'date', 'level', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function clients(){
        return $this->hasMany(Client::class);
    }

    public function partners(){
        return $this->belongsTo(Partner::class);
    }

}
