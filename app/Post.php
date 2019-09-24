<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    //by default Laravel uses guarded to prevent the user from creating new form fields that do not
    // exist in order to submit arbitrary data to our database, but if you EXPLICITILY define your fields properly in the CONTROLLER you can turn this feature off (check PostController) validation
    // make guarded = [] turns it off
    protected $guarded = [];
    public function user(){
       return $this->belongsTo(User::class);
    }
}
