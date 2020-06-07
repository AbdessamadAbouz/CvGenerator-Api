<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Competence;

class CompetenceType extends Model
{
    protected $guarded = [];

    public function competences() 
    {
        return $this->hasMany(Competence::class);
    }
}
