<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commentreaction;

class CommentreactionController extends Controller
{
        /**
     * Show the form for creating a new resource.
     *
     * @return App\Models\CommentReaction 
     */
    public function create($comment_id, $user_id, $type)
    {

        $reaction = new Commentreaction();

        $reaction->user_id = $user_id;
        $reaction->comment_id = $comment_id;
        $reaction->type = $type;
        
        $reaction->save();

        return $reaction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return 
     */
    public function destroy($id)
    {
        $this->delete();
    }


    /**
     * Update the specified resource in storage.
     *
     */
    public function update($type)
    {
        $this->type = $type; 
        $this->save();
    }


}
