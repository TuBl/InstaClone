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
    // \App\User makes it know this var is of this model and also does findOrFail above ^
    // we can also just Say User, since we are importing it at the top use App\User;
    public function edit(User  $user){
        //use policy to authroize update //if u are authorized to update, check ProfilePolicy
        $this->authorize('update', $user->profile);
        //compact instead of arry like above, i left both for comparison
        return view('profiles.edit', compact('user'));
    }

    public function update(User $user){
        //use policy to authroize update //if u are authorized to update, check ProfilePolicy
         $this->authorize('update', $user->profile);
        $data = request()->validate([
            'title'=>'required',
            'description'=>'required',
            'url'=>'url',
            'image'=>'',
        ]);
        //fillable problem unless we remove guarded in Profile model, remember to ALWAYS becareful how
        // your bring in EACH field in validation if u are gonna do this

        // when ever you do update/edit dont trust user, get Authenticated user
        auth()->user->profile->update($data);
        return redirect("/profile/{$user->id}");
    }
}
