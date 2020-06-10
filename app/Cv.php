<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\PersonalInfo;

class Cv extends Model
{
    public function users() 
    {
        return $this->belongsTo(User::class);
    }

    public function personal_infos()
    {
        return $this->belongsTo(PersonalInfo::class);
    }
}
