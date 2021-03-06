<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\PersonalInfo;
use App\Experience;
use App\Formation;
use App\Langue;
use App\Competence;
use App\CompetenceType;
use App\Cv;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token','created_at','updated_at','email_verified_at'];

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

    public function langues()
    {
        return $this->hasMany(Langue::class);
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class,'competence_users','user_id','competence_id');
    }

    public function cvs() 
    {
        return $this->hasMany(Cv::class);
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
