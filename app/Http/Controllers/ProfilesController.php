<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
//for caching info
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;   
class ProfilesController extends Controller
{
    //
    public function index(User $user){
        //echo function
        //dd($user);
        //User is not in the same name space as Profile
        //IMPRT VIA use App\User
        // $user = User::findOrFail($user); 
        // return view('profiles.index', [
        //     'user' => $user,
        // ]);
        // see if the user is authenticated, check if the auth user following contains the id of the User profile we hit follow on
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        // $postCount = $user->posts->count(); post count without 
        //with caching we need to give it a key to remember, for Each userID so that it is unique
        // dont forget to use($user) to have access to it inside of our closure function
        // dont forget the 2nd argument is the cache duration
        $postCount = Cache::remember(
            'count.posts.'. $user->id, 
            now()->addSeconds(30), 
            function() use($user){
                return $user->posts->count();
        });
        $followersCount = Cache::remember(
            'count.followers.' . $user->id,
            now()->addSeconds(30),
            function() use($user){
                return $user->posts->count();
            });
        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(30),
            function() use($user){
                return $user->following->count();
            });
        //after using User $user
        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
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
            
        // this broke on me, because we always assumed we had an image (where is your ELSE!!).. so no image, no ImgURI? but we always assumed that there is and we set $data,['image'=>$ImgURI] (check sol with ***)

        if(request('image')){
            $ImgURI = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/{$ImgURI}"))->fit(1000, 1000);
            $image->save();
            //***
            $imageArray = ['image' => $ImgURI];

        }
    
        // when ever you do update/edit dont trust user, get Authenticated user
        // we can use array_merge to merge two arrays, by passing the key name with new value we can over ride the value in the first array with a new one

        auth()->user()->profile->update(array_merge(
            // check if imageArray is set, if not default to an empty array so you don't OVERRIDE the default image
            //else the new image from above is passed into our data
            $data,
            $imageArray ?? []
            ));

        return redirect("/profile/{$user->id}");
    }
}
