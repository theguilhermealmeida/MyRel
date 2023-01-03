<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Postreaction;
use App\Http\Controllers\PostreactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{   
    /**
     * Shows all the user posts.
     *
     * @return Response
     */
    public function list()
    {
        if (!Auth::check()) return redirect('/login');
        $posts = Auth::user()->posts()->orderBy('id')->get();
        return view('pages.posts', ['posts' => $posts]);
    }

    
    /**
     * Gives a list of posts allowed to be seen by a user with given id
     * 
     * @param int $id
     * @return Collection
     */
    public function allowed_posts($user_id)
    {   
        $relations = User::find($user_id)->relationships()->get();
        $relations = $relations->merge(User::find($user_id)->relationships2()->get());
        $posts = new \Illuminate\Database\Eloquent\Collection;
        foreach ($relations as $relation) {
            if($relation->pivot->state=='accepted'){
                if ($relation->pivot->type=== 'Family') {
                    $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('visibility','Family'));
                    $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('visibility','Friends'));
                }
                else if ($relation->pivot->type === 'Friends') {
                    $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('visibility','Friends'));
                }
                else if ($relation->pivot->type === 'Close Friends') {
                    $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('visibility','Close Friends'));
                    $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('visibility','Friends'));
                }
            }
        }
        $posts = $posts->merge(Post::all()->where('user_id',$user_id));
        $posts = $posts->merge(Post::where('visibility',NULL)->get());
        return $posts;
    }

    /**
     * Gives a list of posts allowed to be seen by a user with given id
     * 
     * @param Collection $posts
     * @return Collection
     */
    public function getComments($posts)
    {
        $comments = new \Illuminate\Database\Eloquent\Collection;
        foreach($posts as $post){
            $comments = $comments->merge($post->comments()->get());
        }
        return $comments;    
    }


    /**
     * Shows all relations posts.
     *
     * @return Response
     */
    public function feed(Request $request)
    {
        if (!Auth::check()){
            $posts = Post::where('visibility',NULL)->get();
        }
        else{

            if ($request->input('type') === 'family') {
                $posts = $this->allowed_posts(Auth::user()->id)->where('visibility','Family');
            }
            else if ($request->input('type') === 'friends') {
                $posts = $this->allowed_posts(Auth::user()->id)->where('visibility','Friends');
            }
            else if ($request->input('type') === 'closefriends') {
                $posts = $this->allowed_posts(Auth::user()->id)->where('visibility','Close Friends');
            } 
            else {
                if (!Auth::check()){
                    $posts = Post::where('visibility',NULL)->get();
                }
                else{
                    $posts = $this->allowed_posts(Auth::user()->id);
                }   
            }
        }   
        return view('pages.posts', ['posts' => $posts->sortBy('id')->reverse()]);
    }
    
    
    

    /**
     * Shows the post for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        
    return view('pages.post', ['post' => $post]);
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
    public function create(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = new Post();

        $post->user_id = Auth::user()->id;
        $post->text = $request->input('text');
        if ($request->input('visibility') == 'Strangers') {
            $post->visibility = NULL; 
        }
        else {
            $post->visibility = $request->input('visibility');
        }

        if($request->hasFile('image')){         
            $imageName = time().'.'.$request->image->extension(); 
            $request->image->move(public_path('post_images'), $imageName);
            $post->photo = "/post_images/". $imageName;
        }
        
        $post->save();

        return redirect('posts');
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
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $post = Post::find($request->id);
        $post->text = $request->input('text');
        if ($request->input('visibility') == 'Strangers') {
            $post->visibility = NULL; 
        }
        else {
            $post->visibility = $request->input('visibility');
        }

        if($request->hasFile('image')){   
            $image_path = $post->photo;
            if (File::exists(public_path($image_path))) {
                File::delete(public_path($image_path));
            }      
            $imageName = time().'.'.$request->image->extension(); 
            $request->image->move(public_path('post_images'), $imageName);
            $post->photo = "/post_images/". $imageName;
        }

        $post->save();

        return redirect('posts/'.$post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $post = Post::find($request->id);
        $image_path = $post->photo;
        if (File::exists(public_path($image_path))) {
            File::delete(public_path($image_path));
        }
        $post->delete();
        return redirect('posts');
    }

    public function addReaction(Request $request, $post_id) 
    {

         if (!Auth::check())
        {
            return redirect()->back()->withErrors([
                'You must be logged in to create a reaction',
            ]);
        }

        $request->validate([
            'reaction_type' => 'required|in:Like,Dislike,Sad,Angry,Amazed',
        ]);

        $user = Auth::user();
        $post = Post::find($post_id); 

        // check if the user has already reacted to the post

        $reaction = Postreaction::where('user_id', $user->id)->where('post_id', $post->id)->first();

        // user already reacted to the post
        if ($reaction)
        {

            // wants to change reaction type
            if ($reaction->type != $request->input('reaction_type'))
            {
                $reaction->update(['type' => $request->input('reaction_type')]);
            }
            // user wants to remove the reaction
            else
            {
                $reaction->destroy($reaction->id);
            }
            
        }
        // user has not reacted to the post
        else 
        {
            $new_reaction = Postreaction::create(['user_id' => $user->id, 'post_id' => $post_id, 'type' => $request->input('reaction_type')]);
        }

        // return response()->json([
        //     'message' => 'Reaction added successfully',
        // ]);
        return redirect()->back()->with('sucess', 'you added a reaction!');
    }

    public function get_reactions($id)
    {
        $post = Post::find($id);
        return view('pages.post_reactions', ['post' => $post]);
    }

}