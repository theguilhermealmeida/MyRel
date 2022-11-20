<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
     * Shows all relations posts.
     *
     * @return Response
     */
    public function feed()
    {
        if (!Auth::check()) return redirect('/login');
        $posts = Post::all();
        return view('pages.posts', ['posts' => $posts]);
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
        $post->visibility = $request->input('visibility');
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
        $post->visibility = $request->input('visibility');
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
