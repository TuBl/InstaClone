<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class ProfilesController extends Controller
{
    //
    public function index($user){
        //echo function
        //dd($user);
        //User is not in the same name space as Profile
        //IMPRT VIA use App\User
        $user = User::findOrFail($user); 
        return view('profiles.index', [
            'user' => $user,
        ]);
    }
}
