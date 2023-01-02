<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replyreaction extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

      /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'reply_id', 'type'
  ];



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