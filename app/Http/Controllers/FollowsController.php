<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{

    //use constructor to make auth middleware required (return unauthorized 401 to use in catch-block)
    public function __construct(){
        $this->middleware('auth');
    }
    //
    public function store(User $user){
        //user we are refering to with $user = user being passed to us
        // we are using the auth user to establish the connection
        return auth()->user()->following()->toggle($user->profile);
    }
}
