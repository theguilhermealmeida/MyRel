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
            $first = Post::search($request->search)->simplePaginate(20);
            $posts = Post::where('visibility',NULL)->get()->intersect($first);
        }
        else{
            $first = Post::search($request->search)->simplePaginate(20);
            $posts = (new PostController)->allowed_posts(Auth::user()->id)->intersect($first);                      
        }

        $posts = $posts-> slice(0,5);
        $comments = Comment::search($request->search)->simplePaginate(5);
        $users = User::search($request->search)->simplePaginate(5);
        return view('pages.search', ['posts' => $posts,'search'=>$request->search,'comments'=>$comments,'users'=>$users]);
    }

}
