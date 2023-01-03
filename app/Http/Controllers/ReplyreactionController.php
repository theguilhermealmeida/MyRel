<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Replyreaction;

class ReplyreactionController extends Controller
{
        /**
     * Show the form for creating a new resource.
     *
     * @return App\Models\ReplyReaction 
     */
    public function create($reply_id, $user_id, $type)
    {

        $reaction = new Replyreaction();

        $reaction->user_id = $user_id;
        $reaction->reply_id = $reply_id;
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
