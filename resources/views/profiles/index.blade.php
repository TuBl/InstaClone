@extends('layouts.app')

@section('content')
<div class="container">
 <div class="row">
     <div class="col-3 p-5">
     <img src="{{$user->profile->profileImage()}}" alt="logo" class = "rounded-circle w-100">
     </div>
     <div class="col-9 pt-5">
         <div class="d-flex justify-content-between align-items-baseline">
             {{-- the echo in php is as follows  --}}
             {{-- </?=?> remove the / i put it there not to break the comment--}}
             {{-- in laravel just do {{}} --}}
             <div class="d-flex align-items-center">
                    <div class="h4">{{$user->username}}</div>
             <follow-button user-id ="{{$user->id}}" follows = {{$follows}}></follow-button>
             </div>
             @can('update', $user->profile)
                <a href="/p/create">Add new post</a>
             @endcan    
         </div>
         {{-- If the user CAN update (policy needs profile/user) --}}

         @can('update', $user->profile)
            <a href="/profile/{{$user->id}}/edit">Edit profile</a>
         @endcan
         <div class = "d-flex">
         <div class = "pr-5"><strong>{{$postCount}}</strong> posts</div>
         <div class = "pr-5"><strong>{{$followersCount}}</strong> followers</div>
         <div class = "pr-5"><strong>{{$followingCount}}</strong> following</div>
         </div>
     
             <div class="pt-4 font-weight-bold">{{$user->profile->title}}</div>
             <div >
                 {{$user->profile->description}}
             </div>
             <div>
                <a href="#
                " style = "color: #003569; font-size 16px;">{{$user->profile->url}}
               </a>
             </div> 
 
        </div>  
    </div>  
            <div class="row pt-4">
                @foreach($user->posts as $post)
                    <div class="col-4 pb-4">
                        <a href="/p/{{$post->id}}">
                            <img src= "/storage/{{$post->image}}" alt="" class = "w-100 h-100">
                        </a>
                    </div>
                @endforeach

            </div>
     </div>
 </div>
</div>
@endsection


