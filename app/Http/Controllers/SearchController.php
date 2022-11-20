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
        $posts = Post::search($request->search)->simplePaginate(5);
        $comments = Comment::search($request->search)->simplePaginate(5);
        $users = User::search($request->search)->simplePaginate(5);
        return view('pages.search', ['posts' => $posts,'search'=>$request->search,'comments'=>$comments,'users'=>$users]);
    }

}
