<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Commentreaction;
use App\Http\Controllers\CommentreactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
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

        $comment = new Comment();

        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->input('postId');
        $comment->text = $request->input('text');
        
        $comment->save();

        return redirect('posts/'.$request->input('postId'));
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
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $comment = Comment::find($request->id);
        $comment->text = $request->input('text');

        $comment->save();

        return redirect('posts/'.$comment->post_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $comment = Comment::find($request->id);
        $id = $comment->post_id;
        $comment->delete();
        return redirect('posts/'.$id);
    }

    public function addReaction(Request $request, $comment_id) 
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
        $comment = Comment::find($comment_id); 

        // check if the user has already reacted to the comment

        $reaction = Commentreaction::where('user_id', $user->id)->where('comment_id', $comment->id)->first();

        // user already reacted to the comment
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
        // user has not reacted to the comment
        else 
        {
            $new_reaction = Commentreaction::create(['user_id' => $user->id, 'comment_id' => $comment_id, 'type' => $request->input('reaction_type')]);
        }

        // return response()->json([
        //     'message' => 'Reaction added successfully',
        // ]);
        return redirect()->back()->with('sucess', 'you added a reaction!');
    }

}
