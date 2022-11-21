<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replyreaction extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The user this replyreaction belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The reply this reaction belongs to
   */
  public function reply() {
    return $this->belongsTo('App\Models\Reply');
  }
  
}