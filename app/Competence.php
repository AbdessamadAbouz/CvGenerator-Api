<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CompetenceType;
use App\CompetenceUser;
use App\User;

class Competence extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at','updated_at','pivot','competence_users'];
    protected $appends = ['competence_type'];

    public function getCompetenceTypeAttribute()
    {
        return CompetenceType::find($this->competence_type_id)->label;
    }

    public function getUserAttribute()
    {
        return User::find($this->competence_users->user_id);
    }

    public function competence_types()
    {
        return $this->belongsTo(CompetenceType::class);
    }

    public function competence_users()
    {
        return $this->hasOne(CompetenceUser::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'competence_users','competence_id','user_id');
    }
}
