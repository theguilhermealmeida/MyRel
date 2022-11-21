<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{   
    /**
     * Get all data in website.
     *
     * @return Response
     */
    public function admin()
    {   
        $posts = Post::all();
        $comments = Comment::all();
        $users = User::all();
        $replies = Reply::all();
        return view('pages.admin', ['posts' => $posts,'comments'=>$comments,'users'=>$users, 'replies'=>$replies]);
    }

}
