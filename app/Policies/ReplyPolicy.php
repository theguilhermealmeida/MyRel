<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Reply $reply) 
    {
      // Only a post owner can update its own posts
      return ($user->id === $reply->user_id) or $user->id ==0 ;
    }

    public function create(User $user)
    {
      // Any user can create a new post 
      return Auth::check();
    }

    public function delete(User $user, Reply $reply)
    {
      // Only a post owner can delete it
      return ($user->id === $reply->user_id) or $user->id ==0 ;
    }
}