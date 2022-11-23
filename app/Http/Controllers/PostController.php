<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            if($relation->pivot->type == 'Family'){
                $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('family',TRUE));
            }else{
                $posts = $posts->merge(Post::all()->where('user_id',$relation->id)->where('visibility',$relation->pivot->type));
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
    public function feed()
    {
        if (!Auth::check()){
            $posts = Post::where('visibility',NULL)->get();
        }
        else{
            $posts = $this->allowed_posts(Auth::user()->id);
        }   
        return view('pages.posts', ['posts' => $posts->sortBy('id')]);
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
        $post = new Post();

        $post->user_id = Auth::user()->id;
        $post->text = $request->input('text');
        if ($request->input('visibility') == 'Strangers') {
            $post->visibility = NULL; 
        }
        else {
            $post->visibility = $request->input('visibility');
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
        
        $post = Post::find($request->id);
        $post->text = $request->input('text');
        if ($request->input('visibility') == 'Strangers') {
            $post->visibility = NULL; 
        }
        else {
            $post->visibility = $request->input('visibility');
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
        $post->delete();
        return redirect('posts');
    }
}
