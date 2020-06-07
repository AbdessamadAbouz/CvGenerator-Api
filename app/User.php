<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\PersonalInfo;
use App\Experience;
use App\Formation;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guarded = [];


    protected $hidden = ['password', 'remember_token',];

    protected $casts = ['email_verified_at' => 'datetime'];

    //Relations between tables
    //
    public function personal_infos()
    {
        return $this->hasMany(PersonalInfo::class);
    }
    
    public function experiences() 
    {
        return $this->hasMany(Experience::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    //JWT connector
    //
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
