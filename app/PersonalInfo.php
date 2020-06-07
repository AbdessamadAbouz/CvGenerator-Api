<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PersonalInfo extends Model
{
    protected $guarded = [];
    protected $appends = ['user'];
    public function users() {
        return $this->belongsTo(User::class);
    }

    public function getUserAttribute() {
        return User::find($this->user_id);
    }

    //Save image to the path
    //
    public function saveImage($image) {
        $destinationPath = 'public/image/'; // upload path
        $profileImage = $this->nom . $this->prenom . $this->generateRandomString() . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $profileImage);

        $this->profil_picture = $destinationPath . $profileImage;
        $this->save();
    }

    // Generate a random string to use on file names
    //
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
