<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];
    //default img if no profile img
    // for some reason the app didnt like us making the conditional statement with the return, make a variable..
    public function profileImage(){
        $imgURI = ($this->image) ? $this->image : 'profile/GTsFYgyXyZ6eMG7S6IH22s1MOkt92diVAA89W9bl.png';
        return '/storage/' .$imgURI;   
    }
    
    public function followers(){
        return $this->belongsToMany(User::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
