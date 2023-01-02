<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postreaction;

class PostreactionController extends Controller
{
        /**
     * Show the form for creating a new resource.
     *
     * @return App\Models\PostReaction 
     */
    public function create($post_id, $user_id, $type)
    {

        $reaction = new Postreaction();

        $reaction->user_id = $user_id;
        $reaction->post_id = $post_id;
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
        // $reaction = Postreaction::find($reaction_id);
        // $reaction->delete();
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
