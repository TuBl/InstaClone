<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //function that runs when new instance of this model is fired up
    protected static function boot(){
        //keep parent boot
        parent::boot();
        //fire an event when a User is "created" check docs for other events such as saving/saved, creating/created....
        // this closure accepts a model, we can pass it as a var in this case $user
        //the profile is created via the relationship, all that is needed is the user ID, u can fill other fields as necessary
        static::created(function ($user){
            $user->profile()->create([
                'title' => $user->username,
            ]);

        });
    }

    public function profile(){
        //same name space as Profile no need to do App::Profile or 
        // use import statement
        return $this->hasOne(Profile::class);
    }

    public function following(){
        return $this->belongsToMany(Profile::class);
    }
    public function posts(){
        // created_at is automaticxally created in the migration
        //we can use it to order the items
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }
}
