<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The user this post belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

  /**
  * The comments this post has.
  */
  public function comments() {
      return $this->hasMany('App\Models\Comment');
  }

    /**
     * The postreactions that belong to the post.
     */
    public function reactions() {
      return $this->hasMany('App\Models\Postreaction');
  }
  
}
