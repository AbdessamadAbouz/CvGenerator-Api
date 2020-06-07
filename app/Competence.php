<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CompetenceType;

class Competence extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class,'competence_users','user_id','competence_id');
    }

    public function competence_types()
    {
        return $this->belongsTo(CompetenceType::class);
    }
}
