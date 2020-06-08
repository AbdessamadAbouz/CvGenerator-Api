<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CompetenceType;
use App\CompetenceUser;
use App\User;

class Competence extends Model
{
    protected $guarded = [];

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
