<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
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
        $posts = Post::query();
        $posts = $posts->where('text','LIKE','%'.$request->search."%")->get();
        $comments = Comment::query();
        $comments = $comments->where('text','LIKE','%'.$request->search."%")->get();
        $users = User::query();
        $users = $users->where('name','LIKE','%'.$request->search."%")->get();
        return view('pages.search', ['posts' => $posts,'search'=>$request->search,'comments'=>$comments,'users'=>$users]);
    }

}
