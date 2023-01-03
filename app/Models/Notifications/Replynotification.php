<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Replynotification extends Model
{
    // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $fillable = [
    'read'
]; 

  /**
   * The user this Replynotification belongs to
   */
  public function user() {
    return $this->belongsTo('App\Models\User');
  }

    /**
   * The reply this Replynotification belongs to
   */
  public function reply() {
    return $this->belongsTo('App\Models\Reply');
  }

  /**
   * 
   */
  
}
