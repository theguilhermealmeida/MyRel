<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{   
    /**
     * Search for posts.
     *
     * @return Response
     */
    public function search(Request $request)
    {   
        if (!Auth::check()){
            $search_posts = Post::search($request->search)->simplePaginate(20);
            $visible_posts = Post::where('visibility',NULL)->get();
        }
        else{
            $search_posts = Post::search($request->search)->simplePaginate(20);
            $visible_posts = (new PostController)->allowed_posts(Auth::user()->id);                   
        }
        $posts = $visible_posts->intersect($search_posts); 
        $posts = $posts-> slice(0,5);
        $visible_comments = (new PostController)->getComments($visible_posts);
        $comments = $visible_comments->intersect(Comment::search($request->search)->simplePaginate(40));
        $users = User::search($request->search)->simplePaginate(5);
        return view('pages.search', ['posts' => $posts,'search'=>$request->search,'comments'=>$comments,'users'=>$users]);
    }



    public function search_api(Request $request)
    {
        if (!Auth::check()){
            $search_posts = Post::search($request->search)->simplePaginate(20);
            $visible_posts = Post::where('visibility',NULL)->get();
        }
        else{
            $search_posts = Post::search($request->search)->simplePaginate(20);
            $visible_posts = (new PostController)->allowed_posts(Auth::user()->id);                   
        }
        $posts = $visible_posts->intersect($search_posts); 
        $posts = $posts-> slice(0,5);
        $visible_comments = (new PostController)->getComments($visible_posts);
        $comments = $visible_comments->intersect(Comment::search($request->search)->simplePaginate(40));
        $users = User::search($request->search)->simplePaginate(5);
        
        return response()->json(['posts' => $posts,'search'=>$request->search,'comments'=>$comments,'users'=>$users]);
    }
    
    

}
