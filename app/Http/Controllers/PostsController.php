<?php

namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
class PostsController extends Controller
{
    //if you want EVERY SINGLE ROUTE/FUNCTION to require authentication
    //if you go to a route that has this, while not auth laravel redirects you to login page
    public function __construct(){

        $this->middleware('auth');

    }
    public function create(){
        return view('posts.create');
    }
    public function index(){
        //pluck is usefull to "pluck" (fetch) a value
        $users = auth()->user()->following()->pluck('profiles.user_id');
        // dont forget to import post model
        // whereIn = we pass in array of data and compare a value to the list of values from the array
        //latest replaces ->orderBy('created_at', 'DESC')
        //instead of using ->latest()->get() we use paginate to make it show x# of records/page 
        // with user helps solving the n+1 (limit = 1) query problem instead of multiple queries, we preload the user
        // user refers to the relationship in the posts model
        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(3);
        return view('posts.index', compact('posts'));
    }

    public function store(){
        //if we try to create a Post without having userId (we need to make it use the auth user id)
        // it will give us  Integrity constraint violation, we must create the post via the Relationship..
        $data = request()->validate([
            'caption' => 'required',
            'image' => ['required', 'image'], 
            // 'image' => 'required|image'  same as above
            // if you had a field that didnt need validation but you wanna include in the data array to easily pass it to create
            // for example, you can do it as follow
            // 'unValidatedFiled' => '',    
        ]);
        //first argu to store is the dir / is our uploads root so /uploads creates new dir uploads
        //2nd arg is driver, example amazon s3, you have to set up your credentials and laravel handles the rest
        // for now we use local storage, in our storage/app/public dir
        // public is not accesible, we need to run php artisan storage:link once in the life
        // of the project
        $ImgURI = request('image')->store('uploads', 'public');
        $image = Image::make(public_path("storage/{$ImgURI}"))->fit(1200, 1200);
        $image->save();

        //create a post with our data this doesnt work because no user_id
        // \App\Post::create($data);

        // to get the Authenticated user we use auth, laravel adds user_id for us
        // mustb e signed in!
        // since we wanna add ImgURI which is not in data, manual time :'(
        // auth()->user()->posts()->create($data);
        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $ImgURI,
        ]);
        return redirect("/profile/" . auth()->user()->id);

        // dd(request()->all()); 
    }
    // if we add the \App\Post (Route / model binding) 
    // name var same in route / controller
    // add the model before var name in controller
    // Laravel will try to fetch the resource for you!
    // it also does findOrFail for you
    public function show(\App\Post $post){
        //use compact instead o passing in array
        // add more var like this compact('post', 'anotherOne')
        return view('posts.show', compact('post'));
    }
}
