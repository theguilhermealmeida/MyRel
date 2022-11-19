<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postreaction extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  /**
   * The user this postreaction belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The post this reaction belongs to
   */
  public function post() {
    return $this->belongsTo('App\Models\Post');
  }
  
}
