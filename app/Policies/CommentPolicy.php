<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment) 
    {
      // Only a post owner can update its own posts
      return ($user->id === $comment->user_id) or $user->id ==0 ;
    }

    public function create(User $user)
    {
      // Any user can create a new post 
      return Auth::check();
    }

    public function delete(User $user, Comment $comment)
    {
      // Only a post owner can delete it
      return ($user->id === $comment->user_id) or $user->id ==0 ;
    }
}