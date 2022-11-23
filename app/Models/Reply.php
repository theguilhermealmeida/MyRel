<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The user this reply belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The comment this reply belongs to
   */
  public function comment() {
    return $this->belongsTo('App\Models\Comment');
  }

      /**
     * The reactions that belong to the reply.
     */
    public function reactions() {
      return $this->hasMany('App\Models\Replyreaction');
  }

}
