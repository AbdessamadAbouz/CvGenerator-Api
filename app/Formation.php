<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Formation extends Model
{
    protected $guarded = [];
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
