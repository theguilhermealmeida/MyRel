<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Comment;
use App\Models\Replyreaction;
use App\Http\Controllers\ReplyreactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
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

        $reply = new Reply ();
        $comment = Comment::find($request->input('commentId'));

        $reply->user_id = Auth::user()->id;
        $reply->comment_id = $request->input('commentId');
        $reply->text = $request->input('text');
        
        $reply->save();

        return redirect('posts/'.$comment->post_id);
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
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $reply = Reply::find($request->id);
        $reply->text = $request->input('text');
        $comment = Comment::find($reply->comment_id);

        $reply->save();

        return redirect('posts/'.$comment->post_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $reply = Reply::find($request->id);
        $comment = Comment::find($reply->comment_id);
        $id = $comment->post_id;
        $reply->delete();
        return redirect('posts/'.$id);
    }

    public function addReaction(Request $request, $reply_id) 
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
        $reply = Reply::find($reply_id); 

        // check if the user has already reacted to the reply

        $reaction = Replyreaction::where('user_id', $user->id)->where('reply_id', $reply->id)->first();

        // user already reacted to the reply
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
        // user has not reacted to the reply
        else 
        {
            $new_reaction = Replyreaction::create(['user_id' => $user->id, 'reply_id' => $reply_id, 'type' => $request->input('reaction_type')]);
        }

        // return response()->json([
        //     'message' => 'Reaction added successfully',
        // ]);
        return redirect()->back()->with('sucess', 'you added a reaction!');
    }

}
