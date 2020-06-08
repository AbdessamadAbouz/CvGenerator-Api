<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Competence;

class CompetenceUser extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function competences()
    {
        return $this->belongsTo(Competence::class);
    }
}
