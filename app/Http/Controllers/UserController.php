<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    /**
     * Shows the user profile for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    
    $user = User::find($id);
    
    $pending_relationships = $user->relationships()->where('state', 'pending')->orderBy('id')->get();
    $pending_relationships = $pending_relationships->merge($user->relationships2()->where('state', 'pending')->orderBy('id')->get());

    $friends = $user->relationships()->where('state', 'accepted')->where('type', 'Friends')->orderBy('id')->get();
    $friends = $friends->merge($user->relationships2()->where('state', 'accepted')->where('type', 'Friends')->orderBy('id')->get());

    $close_friends = $user->relationships()->where('state', 'accepted')->where('type', 'Close Friends')->orderBy('id')->get();
    $close_friends = $close_friends->merge($user->relationships2()->where('state', 'accepted')->where('type', 'Close Friends')->orderBy('id')->get());

    $family = $user->relationships()->where('state', 'accepted')->where('type', 'Family')->orderBy('id')->get();
    $family = $family->merge($user->relationships2()->where('state', 'accepted')->where('type', 'Family')->orderBy('id')->get());

    $all_rel = $friends->merge($close_friends->merge($family->merge($pending_relationships)));

    $postController = new PostController();
    if (Auth::check()) {
        $auth_id = Auth::user()->id;
        $posts = $postController->allowed_posts($auth_id)->where('user_id',$id);
    }
    else
    {
        $posts = Post::where('visibility', NULL)->where('user_id', $id)->get();
    }

    
    return view('pages.profile', ['posts' => $posts, 'user' => $user, 'pending_relationships' => $pending_relationships, 'friends' => $friends, 'close_friends' => $close_friends, 'family' => $family, 'relationships' => $all_rel]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $user = User::find($request->id);
        $user->name = $request->input('name');
        $user->gender = $request->input('gender');
        $user->description = $request->input('description');

        if($request->hasFile('image')){   
            $image_path = $user->photo;
            if (File::exists(public_path($image_path))) {
                File::delete(public_path($image_path));
            }      
            $imageName = time().'.'.$request->image->extension(); 
            $request->image->move(public_path('profile_images'), $imageName);
            $user->photo = "/profile_images/". $imageName;
        }

        $user->save();
        
        return redirect('user/'. $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function ban(Request $request)
    {
        $user = User::find($request->id);
        if ($user->ban) {
        $user->ban = 'False';
        }
        else {
            $user->ban = 'True';
        }

        $user->save();
        if (Auth::user()->id == 0) {
            return redirect('/admin');
        }
        else {
            return redirect('/logout');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        
    }

    public function marknotificationsasread(Request $request) {
        $user = User::find($request->id);
        $all_notifications = $user->unreadNotifications();
  
        foreach($all_notifications as $notification) {
          $notification->read = 1;
          $notification->save();
        }

        return redirect()->back();
      }


    public function showNotifications(Request $request)
    {
      if (Auth::check())
      {
        if (Auth::user()->id == $request->id) 
        {
            $user = User::find($request->id);    
            $notifications = $user->allNotifications();
            return view('pages.notifications', ['user' => $user, 'notifications' => $notifications]);
        }
    
    }
    abort(403);
    }

}
